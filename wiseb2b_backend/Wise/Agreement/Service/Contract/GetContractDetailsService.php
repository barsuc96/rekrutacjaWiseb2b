<?php

namespace Wise\Agreement\Service\Contract;

use Wise\Agreement\Domain\Contract\ContractRepositoryInterface;
use Wise\Agreement\Service\Contract\Interfaces\ContractAdditionalFieldsServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\GetContractDetailsServiceInterface;
use Wise\Core\Service\AbstractDetailsService;

class GetContractDetailsService extends AbstractDetailsService implements GetContractDetailsServiceInterface
{
    public function __construct(
        private readonly ContractRepositoryInterface $repository,
        private readonly ContractAdditionalFieldsServiceInterface $additionalFieldsService
    ){
        parent::__construct($repository, $additionalFieldsService);
    }
}
