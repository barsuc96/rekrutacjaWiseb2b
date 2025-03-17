<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client;

use Exception;
use Wise\Client\Service\Client\Interfaces\ListClientsCountriesServiceInterface;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\I18n\Service\Country\Interfaces\ListCountriesServiceInterface;

/**
 * Serwis aplikacji - dla pobrania listy krajów
 */
class ListClientsCountriesService implements ListClientsCountriesServiceInterface
{
    public function __construct(
        private readonly ListCountriesServiceInterface $listCountriesService,
    ) {
    }

    /**
     * @param ListClientsCountriesParams $params
     * @return CommonServiceDTO
     * @throws Exception
     */
    public function __invoke(ListClientsCountriesParams $params): CommonServiceDTO
    {
        $paramsListCountries = new CommonListParams();

        $paramsListCountries
            ->setFilters([
                new QueryFilter('isActive', true),
                new QueryFilter('limit', null),
            ])
            ->setFields([])
        ;

        $result = ($this->listCountriesService)($paramsListCountries)->read();

        ($resultDTO = new CommonServiceDTO())->writeAssociativeArray($result);

        return $resultDTO;
    }
}
