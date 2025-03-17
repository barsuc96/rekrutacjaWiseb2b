<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Dto\UserAgreements;

use Wise\Core\ApiAdmin\Dto\CommonPutAdminApiDto;

class PutUserAgreementsDto extends CommonPutAdminApiDto
{
    /**
     * @var PutUserAgreementDto[] $objects
     */
    protected array $objects;
}
