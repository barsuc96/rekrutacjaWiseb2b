<?php

declare(strict_types=1);

namespace Wise\User\Service\Agreement\Interfaces;

use Wise\Core\Dto\CommonServiceDTO;

interface RemoveAgreementServiceInterface
{
    public function __invoke(CommonServiceDTO $serviceDTO): CommonServiceDTO;
}
