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
use Wise\Core\Exception\ValidationException;
use Wise\User\ApiUi\Dto\Users\PutUsersMessageSettingsDto;
use Wise\User\ApiUi\Service\Interfaces\PutUsersMessageSettingsServiceInterface;
use Wise\User\Service\UserMessageSettings\Interfaces\AddOrModifyUserMessageSettingsServiceInterface;

class PutUsersMessageSettingsService extends AbstractPutService implements PutUsersMessageSettingsServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        protected readonly TranslatorInterface $translator,
        protected readonly AddOrModifyUserMessageSettingsServiceInterface $service,
    ) {
        parent::__construct($sharedActionService);
    }

    public function put(AbstractDto $dto): void
    {
        /**
         * @var PutUsersMessageSettingsDto $dto
         */
        Assert::isInstanceOf($dto, PutUsersMessageSettingsDto::class);

        ($serviceDTO = new CommonModifyParams())->write($dto, [
            'enabled' => 'isActive',
        ]);

        $serviceDTO->setMergeNestedObjects(true);

        try{

            $resultData = ($this->service)($serviceDTO)->read();
            $this->setParameters(
                message: $this->translator->trans('user.message-settings.modified')
            );
            $this->setData(['enabled' => $resultData['isActive']]);

        }catch (ObjectNotFoundException $e){
            $this->setParameters(
                message: $this->translator->trans('user.message-settings.entity_not_found', ['%messageSettingsId%' => $dto->getMessageSettingsId(), '%userId%' => $dto->getUserId()]),
                messageStyle: ResponseMessageStyle::FAILED->value
            );
            $this->setFieldInfos([
                (new FieldInfoDto())
                    ->setPropertyPath('messageSettingsId')
                    ->setInvalidValue((string) $dto->getMessageSettingsId())
                    ->setMessage($this->translator->trans('user.message-settings.entity_not_found', ['%messageSettingsId%' => $dto->getMessageSettingsId(), '%userId%' => $dto->getUserId()])),
                (new FieldInfoDto())
                    ->setPropertyPath('userId')
                    ->setInvalidValue((string) $dto->getUserId())
                    ->setMessage($this->translator->trans('user.message-settings.entity_not_found', ['%messageSettingsId%' => $dto->getMessageSettingsId(), '%userId%' => $dto->getUserId()])),
            ]);
        }catch (ValidationException $e){
            $this->setParameters(
                message: $this->translator->trans('user.message-settings.settings_not_found', ['%messageSettingsId%' => $dto->getMessageSettingsId()]),
                messageStyle: ResponseMessageStyle::FAILED->value
            );
            $this->setFieldInfos([
                (new FieldInfoDto())
                    ->setPropertyPath('messageSettingsId')
                    ->setInvalidValue((string) $dto->getMessageSettingsId())
                    ->setMessage($this->translator->trans('user.message-settings.settings_not_found', ['%messageSettingsId%' => $dto->getMessageSettingsId()])),
            ]);
        }
    }
}
