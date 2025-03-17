<?php

namespace Wise\Agreement\ApiAdmin\Dto\ContractAgreement;

use Wise\Core\ApiAdmin\Dto\AbstractMultiObjectsAdminApiRequestDto;

class PutContractAgreementsDto extends AbstractMultiObjectsAdminApiRequestDto
{
    /**
     * @var PutContractAgreementDto[] $objects
     */
    protected array $objects;
}
