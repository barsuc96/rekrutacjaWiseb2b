<?php

namespace Wise\Receiver\Service\Receiver;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\I18n\Service\Country\Interfaces\ListCountriesServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ListReceiverCountriesServiceInterface;
use Exception;

class ListReceiverCountriesService implements ListReceiverCountriesServiceInterface
{
    public function __construct(
        private readonly ListCountriesServiceInterface $listCountriesService,
    ) {
    }

    /**
     * @param CommonListParams $params
     * @return CommonServiceDTO
     * @throws Exception
     */
    public function __invoke(CommonListParams $params): CommonServiceDTO
    {
        $paramsListCountries = new CommonListParams();
        $paramsListCountries
            ->setFilters($this->prepareFilters($params))
            ->setFields([]);

        $result = ($this->listCountriesService)($paramsListCountries)->read();

        ($resultDTO = new CommonServiceDTO())->writeAssociativeArray($result);

        return $resultDTO;
    }

    /**
     * Przygotowanie filtrów dla listy krajów
     * @param CommonListParams $params
     * @return array
     */
    protected function prepareFilters(CommonListParams $params): array
    {
        $filters = [
            new QueryFilter('isActive', true),
            new QueryFilter('limit', null),
        ];

        if(!empty($params->getFilters())){
            $filters = array_merge($filters, $params->getFilters());
        }

        return $filters;
    }
}
