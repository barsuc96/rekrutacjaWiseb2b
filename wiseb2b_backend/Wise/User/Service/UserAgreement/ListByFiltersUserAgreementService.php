<?php

declare(strict_types=1);

namespace Wise\User\Service\UserAgreement;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Helper\QueryFilter\QueryParametersHelper;
use Wise\User\Domain\UserAgreement\UserAgreementRepositoryInterface;
use Wise\User\Service\UserAgreement\Interfaces\ListByFiltersUserAgreementServiceInterface;

class ListByFiltersUserAgreementService implements ListByFiltersUserAgreementServiceInterface
{
    public function __construct(
        private readonly UserAgreementRepositoryInterface $repository
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
