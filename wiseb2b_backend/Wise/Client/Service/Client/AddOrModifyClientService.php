<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client;

use Wise\Client\Domain\Client\ClientRepositoryInterface;
use Wise\Client\Service\Client\Interfaces\AddClientServiceInterface;
use Wise\Client\Service\Client\Interfaces\AddOrModifyClientServiceInterface;
use Wise\Client\Service\Client\Interfaces\ModifyClientServiceInterface;
use Wise\Core\Service\AbstractAddOrModifyService;

class AddOrModifyClientService extends AbstractAddOrModifyService implements AddOrModifyClientServiceInterface
{
    public function __construct(
        private readonly ClientRepositoryInterface $repository,
        private readonly ModifyClientServiceInterface $modifyService,
        private readonly AddClientServiceInterface $addService,
    ) {
        parent::__construct($repository, $addService, $modifyService);
    }
}
