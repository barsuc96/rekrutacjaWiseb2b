<?php

declare(strict_types=1);

namespace Wise\Receiver\Service\Receiver;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Helper\QueryFilter\QueryParametersHelper;
use Wise\Receiver\Domain\Receiver\ReceiverRepositoryInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ListByFiltersReceiverServiceInterface;

class ListByFiltersReceiverService implements ListByFiltersReceiverServiceInterface
{
    public function __construct(
        private readonly ReceiverRepositoryInterface $repository
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
