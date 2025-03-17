<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Service;

use Exception;
use InvalidArgumentException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Service\OverLoginUserParams;
use Wise\Security\ApiUi\Dto\PostLogoutDto;
use Wise\Security\ApiUi\Dto\PostOverLoginDto;
use Wise\Security\ApiUi\Service\Interfaces\PostOverLoginServiceInterface;
use Wise\Security\Service\Interfaces\OverLogInUserServiceInterface;

class PostOverLoginService extends AbstractPostService implements PostOverLoginServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly OverLogInUserServiceInterface $service,
        private readonly TranslatorInterface $translator
    ) {
        parent::__construct($sharedActionService);
    }

    /**
     * @param PostLogoutDto $dto
     *
     * @throws Exception
     */
    public function post(AbstractDto $dto): void
    {
        if (!($dto instanceof PostOverLoginDto)) {
            throw new InvalidArgumentException('Niepoprawne DTO otrzymane na wejście requesta: ' . $dto::class);
        }

        $overLoginUserParams = new OverLoginUserParams();
        $overLoginUserParams->setUserId($dto->getToSwitchUserId());

        ($this->service)($overLoginUserParams);

        $this->setParameters(
            message: $this->translator->trans('security.over_login.success'),
            showMessage: false
        );
    }
}
