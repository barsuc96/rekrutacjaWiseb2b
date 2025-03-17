<?php

namespace Wise\Client\Service\ClientGroup;

use Wise\Client\Domain\ClientGroup\ClientGroupRepositoryInterface;
use Wise\Client\Service\ClientGroup\Interfaces\ModifyClientGroupServiceInterface;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractModifyService;

/**
 * Serwis aplikacji modyfikujący encję Grupy klientów (ClientGroup)
 */
class ModifyClientGroupService extends AbstractModifyService implements ModifyClientGroupServiceInterface
{
    public function __construct(
        private readonly ClientGroupRepositoryInterface $clientGroupRepository,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ){
        parent::__construct($clientGroupRepository, $persistenceShareMethodsHelper);
    }
}
