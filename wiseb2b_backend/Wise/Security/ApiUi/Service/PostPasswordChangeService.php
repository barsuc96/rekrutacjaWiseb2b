<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Service;

use InvalidArgumentException;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Security\ApiUi\Dto\PostPasswordChangeDto;
use Wise\Security\ApiUi\Service\Interfaces\PostPasswordChangeServiceInterface;
use Wise\Security\Service\Interfaces\PasswordChangeServiceInterface;

class PostPasswordChangeService extends AbstractPostService implements PostPasswordChangeServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly PasswordChangeServiceInterface $service,
    ) {
        parent::__construct($sharedActionService);
    }

    /**
     * @param PostPasswordChangeDto $dto
     */
    public function post(AbstractDto $dto): void
    {
        if (!($dto instanceof PostPasswordChangeDto)) {
            throw new InvalidArgumentException('Niepoprawne DTO otrzymane na wejście requesta: ' . $dto::class);
        }

        ($serviceDTO = new CommonServiceDTO())->write($dto);

        $serviceDTO = ($this->service)($serviceDTO);

        $this->setParameters('Sukces')->setData(['id' => $serviceDTO->read()['id']]);
    }
}
