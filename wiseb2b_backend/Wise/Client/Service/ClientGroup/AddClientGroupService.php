<?php

namespace Wise\Client\Service\ClientGroup;

use Wise\Client\Domain\ClientGroup\ClientGroupRepositoryInterface;
use Wise\Client\Domain\ClientGroup\Factory\ClientGroupFactory;
use Wise\Client\Service\ClientGroup\Interfaces\AddClientGroupServiceInterface;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractAddService;

/**
 * Serwis aplikacji dodający encję Grupy klientów (ClientGroup)
 */
class AddClientGroupService extends AbstractAddService implements AddClientGroupServiceInterface
{
    public function __construct(
        private readonly ClientGroupRepositoryInterface $repository,
        private readonly ClientGroupFactory $entityFactory,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ){
        parent::__construct($repository, $entityFactory, $persistenceShareMethodsHelper);
    }
}
