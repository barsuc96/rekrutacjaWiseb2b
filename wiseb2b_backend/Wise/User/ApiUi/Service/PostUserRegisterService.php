<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Service;

use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Service\Validator\ValidatorServiceInterface;
use Wise\User\ApiUi\Dto\Users\PostUserRegisterRequestDto;
use Wise\User\ApiUi\Service\Interfaces\PostUserRegisterServiceInterface;
use Wise\User\Service\User\Interfaces\RegisterUserServiceInterface;
use Wise\User\Service\User\RegisterUserParams;

class PostUserRegisterService extends AbstractPostService implements PostUserRegisterServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly RegisterUserServiceInterface $registerUserService,
        private readonly TranslatorInterface $translator,
        private readonly ValidatorServiceInterface $validatorService,
    ){
        parent::__construct($sharedActionService);
    }

    public function post(PostUserRegisterRequestDto|AbstractDto $dto): void
    {
        // Walidacja DTO
        $this->validatorService->validate($dto);
        $this->validatorService->handle();

        $commonServiceDTO = new RegisterUserParams();
        $commonServiceDTO->write($dto, []);

        ($this->registerUserService)($commonServiceDTO);

        $this->setParameters(
            message: $this->translator->trans('register.success')
        );
    }
}
