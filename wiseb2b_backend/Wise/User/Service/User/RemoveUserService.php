<?php

declare(strict_types=1);

namespace Wise\User\Service\User;

use JetBrains\PhpStorm\Pure;
use Psr\EventDispatcher\EventDispatcherInterface;
use Wise\Core\ApiAdmin\Service\DeprecatedAbstractRemoveService;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\ValidationException;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Repository\RepositoryManagerInterface;
use Wise\Core\Service\AbstractRemoveService;
use Wise\Core\Service\DomainEventsDispatcher;
use Wise\User\Domain\User\UserAfterRemoveEvent;
use Wise\User\Domain\User\UserBeforeRemoveEvent;
use Wise\User\Domain\User\UserRepositoryInterface;
use Wise\User\Service\User\Interfaces\ListByFiltersUserServiceInterface;
use Wise\User\Service\User\Interfaces\ListUsersServiceInterface;
use Wise\User\Service\User\Interfaces\RemoveUserServiceInterface;

class RemoveUserService extends AbstractRemoveService implements RemoveUserServiceInterface
{

    protected const BEFORE_REMOVE_EVENT_NAME = UserBeforeRemoveEvent::class;
    protected const AFTER_REMOVE_EVENT_NAME = UserAfterRemoveEvent::class;

    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly ListUsersServiceInterface $listService,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ){
        parent::__construct($repository, $listService, $persistenceShareMethodsHelper);
    }

}
