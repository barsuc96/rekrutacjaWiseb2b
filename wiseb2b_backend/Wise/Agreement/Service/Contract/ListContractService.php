<?php

namespace Wise\Agreement\Service\Contract;

use Doctrine\ORM\Cache\Exception\FeatureNotImplemented;
use Wise\Agreement\Domain\Contract\ContractRepositoryInterface;
use Wise\Agreement\Service\Contract\Interfaces\ContractAdditionalFieldsServiceInterface;
use Wise\Agreement\Service\Contract\Interfaces\ListContractServiceInterface;
use Wise\Core\Service\AbstractListService;

class ListContractService extends AbstractListService implements ListContractServiceInterface
{
    /**
     * Czy umożliwiać wyszukiwanie za pomocą searchKeyword
     */
    protected const ENABLE_SEARCH_KEYWORD = true;


    public function __construct(
        private readonly ContractRepositoryInterface $repository,
        private readonly ContractAdditionalFieldsServiceInterface $additionalFieldsService
    ){
        parent::__construct($repository, $additionalFieldsService);
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
        return $this->repository->findByQueryFiltersViewWithLanguages(
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
            'contexts',
        ];
    }
}
