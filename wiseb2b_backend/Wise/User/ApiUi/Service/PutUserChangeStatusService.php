<?php

namespace Wise\User\ApiUi\Service;

use Symfony\Contracts\Translation\TranslatorInterface;
use Webmozart\Assert\Assert;
use Wise\Core\ApiUi\Dto\FieldInfoDto;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPutService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Enum\ResponseMessageStyle;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Security\Exception\RoleHierarchyViolationException;
use Wise\Security\Exception\SelfOperationException;
use Wise\Security\Exception\SuperAdminProtectionException;
use Wise\User\ApiUi\Dto\Users\PutUserChangeStatusRequestDto;
use Wise\User\ApiUi\Service\Interfaces\PutUserChangeStatusServiceInterface;
use Wise\User\Domain\User\CanModifyOtherUserServiceInterface;
use Wise\User\Service\User\Interfaces\ModifyUserServiceInterface;
use Wise\User\Service\User\Interfaces\UserHelperInterface;

class PutUserChangeStatusService extends AbstractPutService implements PutUserChangeStatusServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly UserHelperInterface $userHelper,
        private readonly ModifyUserServiceInterface $modifyUserService,
        private readonly TranslatorInterface $translator,
        private readonly CanModifyOtherUserServiceInterface $canModifyOtherUserService
    )
    {
        parent::__construct($sharedActionService);
    }

    public function put(AbstractDto $dto): void
    {
        /**
         * @var PutUserChangeStatusRequestDto $dto
         */
        Assert::isInstanceOf($dto, PutUserChangeStatusRequestDto::class);

        $serviceDto = new CommonModifyParams();
        $serviceDto->writeAssociativeArray([
            'id' => $dto->getUserId(),
            'isActive' => $dto->getIsActive(),
        ]);

        try{
            $this->canModifyOtherUserService->check($dto->getUserId(), true);

            // Modyfikacja statusu użytkownika
            $resultData = ($this->modifyUserService)($serviceDto)->read();
            $this->setParameters(
                message: $this->translator->trans('user.modified')
            );
            $this->setData(['is_active' => $resultData['isActive']]);

        }catch (ObjectNotFoundException $e){
            $this->setParameters(
                message: $this->translator->trans('user.entity_not_found', ['%id%' => $dto->getUserId()]),
                messageStyle: ResponseMessageStyle::FAILED->value
            );
            $this->setFieldInfos([
                (new FieldInfoDto())
                    ->setPropertyPath('userId')
                    ->setInvalidValue((string) $dto->getUserId())
                    ->setMessage($this->translator->trans('user.entity_not_found', ['%id%' => $dto->getUserId()])),
            ]);
        }catch (SelfOperationException $e){
            $this->setParameters(
                message: $this->translator->trans('user.self_operation', ['%id%' => $dto->getUserId()]),
                messageStyle: ResponseMessageStyle::FAILED->value
            );
            $this->setFieldInfos([
                (new FieldInfoDto())
                    ->setPropertyPath('userId')
                    ->setInvalidValue((string) $dto->getUserId())
                    ->setMessage($this->translator->trans('user.self_operation', ['%id%' => $dto->getUserId()])),
            ]);
        }catch (SuperAdminProtectionException | RoleHierarchyViolationException $e ){
            $this->setParameters(
                message: $this->translator->trans('user.insufficient_permissions', ['%id%' => $dto->getUserId()]),
                messageStyle: ResponseMessageStyle::FAILED->value
            );
            $this->setFieldInfos([
                (new FieldInfoDto())
                    ->setPropertyPath('userId')
                    ->setInvalidValue((string) $dto->getUserId())
                    ->setMessage($this->translator->trans('user.insufficient_permissions', ['%id%' => $dto->getUserId()])),
            ]);
        }
    }
}
