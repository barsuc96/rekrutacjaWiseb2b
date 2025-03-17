<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Service;

use InvalidArgumentException;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Security\ApiUi\Dto\PostLogoutDto;
use Wise\Security\ApiUi\Service\Interfaces\PostLogoutServiceInterface;
use Wise\Security\Service\Interfaces\LogoutServiceInterface;

class PostLogoutService extends AbstractPostService implements PostLogoutServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly LogoutServiceInterface $service,
    ) {
        parent::__construct($sharedActionService);
    }

    /**
     * @param PostLogoutDto $dto
     */
    public function post(AbstractDto $dto): void
    {
        if (!($dto instanceof PostLogoutDto)) {
            throw new InvalidArgumentException('Niepoprawne DTO otrzymane na wejście requesta: '.$dto::class);
        }

        ($serviceDTO = new CommonServiceDTO())->write($dto);

        ($this->service)($serviceDTO);

        $this->setParameters('Sukces');
    }
}
