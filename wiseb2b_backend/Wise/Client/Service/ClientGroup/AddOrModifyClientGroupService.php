<?php

namespace Wise\Client\Service\ClientGroup;

use Wise\Client\Domain\ClientGroup\ClientGroupRepositoryInterface;
use Wise\Client\Service\ClientGroup\Interfaces\AddClientGroupServiceInterface;
use Wise\Client\Service\ClientGroup\Interfaces\AddOrModifyClientGroupServiceInterface;
use Wise\Client\Service\ClientGroup\Interfaces\ModifyClientGroupServiceInterface;
use Wise\Core\Service\AbstractAddOrModifyService;

/**
 * Serwis aplikacji dodaje albo modyfikuje (jeśli istnieje) encję Grupy klientów (ClientGroup)
 */
class AddOrModifyClientGroupService extends AbstractAddOrModifyService implements AddOrModifyClientGroupServiceInterface
{
    public function __construct(
        private readonly ClientGroupRepositoryInterface $repository,
        private readonly AddClientGroupServiceInterface $addService,
        private readonly ModifyClientGroupServiceInterface $modifyService,
    ) {
        parent::__construct($repository, $addService, $modifyService);
    }
}
