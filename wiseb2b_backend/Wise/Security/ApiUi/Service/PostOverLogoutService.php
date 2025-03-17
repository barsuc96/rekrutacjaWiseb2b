<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Service;

use InvalidArgumentException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostService;
use Wise\Core\Dto\AbstractDto;
use Wise\Security\ApiUi\Dto\PostLogoutDto;
use Wise\Security\ApiUi\Dto\PostOverLogoutDto;
use Wise\Security\ApiUi\Service\Interfaces\PostOverLogoutServiceInterface;
use Wise\Security\Service\Interfaces\OverLogOutUserServiceInterface;

class PostOverLogoutService extends AbstractPostService implements PostOverLogoutServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly OverLogOutUserServiceInterface $service,
        private readonly TranslatorInterface $translator
    ) {
        parent::__construct($sharedActionService);
    }

    /**
     * @param PostLogoutDto $dto
     */
    public function post(AbstractDto $dto): void
    {
        if (!($dto instanceof PostOverLogoutDto)) {
            throw new InvalidArgumentException('Niepoprawne DTO otrzymane na wejście requesta: ' . $dto::class);
        }

        ($this->service)();

        $this->setParameters(
            message: $this->translator->trans('security.over_logout.success'),
            showMessage: false
        );
    }
}
