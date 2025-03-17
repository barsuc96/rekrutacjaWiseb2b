<?php

declare(strict_types=1);

namespace Wise\I18n\Service\CurrencyExchange;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Helper\QueryFilter\QueryParametersHelper;
use Wise\I18n\Domain\CurrencyExchange\CurrencyExchangeRepositoryInterface;
use Wise\I18n\Service\CurrencyExchange\Interfaces\ListByFiltersCurrencyExchangeServiceInterface;

class ListByFiltersCurrencyExchangeService implements ListByFiltersCurrencyExchangeServiceInterface
{
    public function __construct(
        private readonly CurrencyExchangeRepositoryInterface $repository
    ) {}

    public function __invoke(array $filters, array $joins, ?array $fields = null): CommonServiceDTO
    {
        $queryParameters = QueryParametersHelper::prepareStandardParameters($filters);

        $entities = $this->repository->findByQueryFiltersView(
            queryFilters:  $queryParameters->getQueryFilters(),
            orderBy: ['field' => $queryParameters->getSortField(), 'direction' => $queryParameters->getSortDirection()],
            limit: $queryParameters->getLimit(),
            offset: $queryParameters->getOffset(),
            fields: $fields,
            joins: $joins
        );

        $entities ??= [];

        ($resultDTO = new CommonServiceDTO())->writeAssociativeArray($entities);

        return $resultDTO;
    }
}
