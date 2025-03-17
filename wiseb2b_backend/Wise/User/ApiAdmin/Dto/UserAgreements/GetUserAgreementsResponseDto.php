<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Dto\UserAgreements;

use Wise\Core\ApiAdmin\Dto\CommonGetAdminApiDto;
use Wise\Core\ApiAdmin\Dto\CommonResponseDto;

class GetUserAgreementsResponseDto extends CommonResponseDto
{
    /** @var GetUserAgreementResponseDto[] $objects */
    protected ?array $objects;

    /** @var GetUserAgreementsQueryParametersDto $inputParameters */
    protected ?CommonGetAdminApiDto $inputParameters;
}
