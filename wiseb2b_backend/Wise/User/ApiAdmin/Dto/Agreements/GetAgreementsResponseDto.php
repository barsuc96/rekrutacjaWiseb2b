<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Dto\Agreements;

use Wise\Core\ApiAdmin\Dto\CommonGetAdminApiDto;
use Wise\Core\ApiAdmin\Dto\CommonResponseDto;

class GetAgreementsResponseDto extends CommonResponseDto
{
    /** @var GetAgreementResponseDto[] $objects */
    protected ?array $objects;

    /** @var GetAgreementsQueryParametersDto $inputParameters */
    protected ?CommonGetAdminApiDto $inputParameters;
}
