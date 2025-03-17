<?php

declare(strict_types=1);

namespace Wise\Client\Domain\ClientGroup\Service;

use Wise\Client\Domain\ClientGroup\ClientGroupRepositoryInterface;
use Wise\Client\Domain\ClientGroup\Exceptions\ClientGroupNotFoundException;
use Wise\Client\Domain\ClientGroup\Service\Interfaces\ClientGroupServiceInterface;
use Wise\Core\Domain\AbstractEntityDomainService;
use Wise\Core\Domain\ShareMethodHelper\EntityDomainServiceShareMethodsHelper;

class ClientGroupService extends AbstractEntityDomainService implements ClientGroupServiceInterface
{
    public function __construct(
        private readonly ClientGroupRepositoryInterface $repository,
        private readonly EntityDomainServiceShareMethodsHelper $entityDomainServiceShareMethodsHelper
    ){
        parent::__construct(
            repository: $repository,
            notFoundException: ClientGroupNotFoundException::class,
            entityDomainServiceShareMethodsHelper: $entityDomainServiceShareMethodsHelper
        );
    }
}
