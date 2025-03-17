<?php

declare(strict_types=1);

namespace Wise\Client\Service\ClientGroup;

use Wise\Client\Domain\ClientGroup\ClientGroupRepositoryInterface;
use Wise\Client\Service\ClientGroup\Interfaces\ClientGroupAdditionalFieldsServiceInterface;
use Wise\Client\Service\ClientGroup\Interfaces\GetClientGroupDetailsServiceInterface;
use Wise\Core\Service\AbstractDetailsService;

class GetClientGroupDetailsService extends AbstractDetailsService implements GetClientGroupDetailsServiceInterface
{
    public function __construct(
        private readonly ClientGroupRepositoryInterface $repository,
        private readonly ClientGroupAdditionalFieldsServiceInterface $additionalFieldsService
    ){
        parent::__construct($repository, $additionalFieldsService);
    }
}
