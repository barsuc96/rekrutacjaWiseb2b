<?php

namespace Wise\Agreement\ApiAdmin\Dto\ContractAgreement;

use Wise\Core\ApiAdmin\Dto\CommonListAdminApiResponseDto;

class GetContractsAgreementsDto extends CommonListAdminApiResponseDto
{
    /** @var GetContractsAgreementDto[] $objects */
    protected ?array $objects;

}
