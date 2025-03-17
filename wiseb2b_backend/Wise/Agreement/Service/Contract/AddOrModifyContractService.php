<?php

namespace Wise\Agreement\Service\Contract;

use Wise\Agreement\Domain\Contract\ContractRepositoryInterface;
use Wise\Agreement\Service\Contract\Interfaces\AddContractServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\AddOrModifyContractServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\ModifyContractServiceInterface;
use Wise\Core\Service\AbstractAddOrModifyService;

class AddOrModifyContractService extends AbstractAddOrModifyService implements AddOrModifyContractServiceInterface
{
    public function __construct(
        private readonly ContractRepositoryInterface $repository,
        private readonly AddContractServiceInterface $addService,
        private readonly ModifyContractServiceInterface $modifyService,
    ) {
        parent::__construct($repository, $addService, $modifyService);
    }
}
