<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client\Interfaces;

use Wise\Client\Service\Client\ListClientsCountriesParams;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

interface ListClientsCountriesServiceInterface extends ApplicationServiceInterface
{
    public function __invoke(ListClientsCountriesParams $params): CommonServiceDTO;
}
