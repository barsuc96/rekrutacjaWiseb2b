<?php

declare(strict_types=1);


namespace Wise\Core\Service\Admin;

use Wise\Core\Helper\QueryFilter\QueryParametersHelper;
use Wise\Core\Repository\Doctrine\ReplicationObjectRepositoryInterface;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\Interfaces\Admin\ListReplicationObjectHistoryServiceInterface;

class ListReplicationObjectHistoryService implements ListReplicationObjectHistoryServiceInterface
{
    public function __construct(
        protected readonly ReplicationObjectRepositoryInterface $replicationObjectRepository,
    ) {
    }

    public function __invoke(CommonListParams $params): array
    {
        $queryParameters = QueryParametersHelper::prepareStandardParameters($params->getFilters());

        return $this->replicationObjectRepository->findByQueryFiltersView(
            queryFilters: $queryParameters->getQueryFilters(),
            orderBy: ['field' => $queryParameters->getSortField(), 'direction' => $queryParameters->getSortDirection()],
            fields: $params->getFields(),
            limit: $queryParameters->getLimit(),
            offset: $queryParameters->getOffset(),
        );
    }
}
