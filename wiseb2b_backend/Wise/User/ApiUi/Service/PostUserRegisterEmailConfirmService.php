<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Service;

use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\ApiUi\Dto\CommonPostUiApiDto;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostService;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\User\ApiUi\Dto\Users\PostUserRegisterEmailConfirmRequestDto;
use Wise\User\ApiUi\Service\Interfaces\PostUserRegisterEmailConfirmServiceInterface;
use Wise\User\Service\User\Interfaces\RegisterEmailConfirmServiceInterface;

/**
 * Serwis obsługuje potwierdzenie maila po rejestracji
 */
class PostUserRegisterEmailConfirmService extends AbstractPostService implements PostUserRegisterEmailConfirmServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly RegisterEmailConfirmServiceInterface $registerEmailConfirmService,
        private readonly TranslatorInterface $translator
    ){
        parent::__construct($sharedActionService);
    }

    public function post(PostUserRegisterEmailConfirmRequestDto|CommonPostUiApiDto $dto): void
    {
        $serviceDto = new CommonServiceDTO();
        $serviceDto
            ->writeAssociativeArray([
                'hash' => $dto->isInitialized('hash') && !empty($dto->getHash()) ? $dto->getHash() : null,
            ]);

        ($this->registerEmailConfirmService)($serviceDto);

        $this->setParameters(
            message: $this->translator->trans('user.email_confirmed'),
        );
    }
}
