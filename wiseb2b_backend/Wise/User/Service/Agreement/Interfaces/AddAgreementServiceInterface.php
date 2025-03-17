<?php

declare(strict_types=1);

namespace Wise\User\Service\Agreement\Interfaces;

use Wise\Core\Dto\CommonModifyParams;

interface AddAgreementServiceInterface
{
    public function __invoke(CommonModifyParams $agreementServiceDto): CommonModifyParams;
}
