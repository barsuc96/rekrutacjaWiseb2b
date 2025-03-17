<?php

declare(strict_types=1);

namespace Wise\Service\Service\Service;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Helper\QueryFilter\QueryParametersHelper;
use Wise\Service\Domain\Service\ServiceRepositoryInterface;
use Wise\Service\Service\Service\Interfaces\ListByFiltersServiceServiceInterface;

class ListByFiltersServiceService implements ListByFiltersServiceServiceInterface
{
    public function __construct(
        private readonly ServiceRepositoryInterface $repository
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
