<?php

declare(strict_types=1);

namespace Wise\User\Service\Agreement;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Helper\QueryFilter\QueryParametersHelper;
use Wise\User\Domain\Agreement\AgreementRepositoryInterface;
use Wise\User\Service\Agreement\Interfaces\ListByFiltersAgreementServiceInterface;

class ListByFiltersAgreementService implements ListByFiltersAgreementServiceInterface
{
    public function __construct(
        private readonly AgreementRepositoryInterface $repository
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
