<?php

declare(strict_types=1);


namespace Wise\Core\Service\Admin;

use Wise\Core\Helper\QueryFilter\QueryParametersHelper;
use Wise\Core\Model\QueryParameters;
use Wise\Core\Repository\Doctrine\ReplicationObjectRepositoryInterface;
use Wise\Core\Repository\Doctrine\ReplicationRequestRepositoryInterface;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\Interfaces\Admin\ListReplicationHistoryServiceInterface;

class ListReplicationHistoryService implements ListReplicationHistoryServiceInterface
{
    public function __construct(
        protected readonly ReplicationRequestRepositoryInterface $replicationRequestRepository,
        protected readonly ReplicationObjectRepositoryInterface $replicationObjectRepository,
    ) {
    }

    public function __invoke(CommonListParams $params): array
    {
        $queryParameters = QueryParametersHelper::prepareStandardParameters($params->getFilters());

        return $this->replicationRequestRepository->findByQueryFilters(
            queryFilters: $queryParameters->getQueryFilters(),
            orderBy: ['field' => $queryParameters->getSortField(), 'direction' => $queryParameters->getSortDirection()],
            fields: $params->getFields(),
            limit: $queryParameters->getLimit(),
            offset: $queryParameters->getOffset(),
        );
    }
}
