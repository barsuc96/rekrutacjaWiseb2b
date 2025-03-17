<?php

namespace Wise\Agreement\Repository\Doctrine;

use Wise\Agreement\Domain\ContractAgreement\ContractAgreement;
use Wise\Agreement\Domain\ContractAgreement\ContractAgreementRepositoryInterface;
use Wise\Core\Repository\AbstractRepository;

class ContractAgreementRepository extends AbstractRepository implements ContractAgreementRepositoryInterface
{
    protected const ENTITY_CLASS = ContractAgreement::class;
}
