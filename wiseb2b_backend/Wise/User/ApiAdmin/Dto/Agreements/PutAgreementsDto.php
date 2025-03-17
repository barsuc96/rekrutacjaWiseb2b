<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Dto\Agreements;

use Wise\Core\ApiAdmin\Dto\CommonPutAdminApiDto;

class PutAgreementsDto extends CommonPutAdminApiDto
{
    /**
     * @var PutAgreementDto[] $objects
     */
    protected array $objects;
}
