<?php

namespace Wise\Service\Service\Service;

use Doctrine\ORM\Cache\Exception\FeatureNotImplemented;
use Wise\Core\Service\AbstractListService;
use Wise\Service\Domain\Service\ServiceRepositoryInterface;
use Wise\Service\Service\Service\Interfaces\ListServiceServiceInterface;

class ListServiceService extends AbstractListService implements ListServiceServiceInterface
{
    /**
     * Czy umożliwiać wyszukiwanie za pomocą searchKeyword
     */
    protected const ENABLE_SEARCH_KEYWORD = true;

    public function __construct(
        private readonly ServiceRepositoryInterface $serviceRepository,
    ){
        parent::__construct($serviceRepository);
    }


    /**
     * Wyszukuje encję na podstawie Query Parameters
     * @param $queryParameters
     * @param $nonModelFields
     * @param $params
     * @param $joins
     * @return array
     * @throws FeatureNotImplemented
     */
    protected function findByQueryFiltersView($queryParameters, $nonModelFields, $params, $joins): array
    {
        return $this->serviceRepository->findByQueryFiltersViewWithLanguages(
            queryFilters: $queryParameters->getQueryFilters(),
            orderBy: ['field' => $queryParameters->getSortField(), 'direction' => $queryParameters->getSortDirection()],
            limit: $queryParameters->getLimit(),
            offset: $queryParameters->getOffset(),
            fields: $this->prepareFields($nonModelFields, $params),
            joins: $joins,
            aggregates: $params->getAggregates() ?? [],
        );
    }

    /**
     * Lista pól, które mają być obsługiwane w filtrowaniu z pola searchKeyword
     * @return string[]
     */
    protected function getDefaultSearchFields(): array
    {
        return [
            'id', 'name'
        ];
    }
}
