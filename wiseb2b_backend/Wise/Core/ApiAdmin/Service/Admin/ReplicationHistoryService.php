<?php

declare(strict_types=1);


namespace Wise\Core\ApiAdmin\Service\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Core\ApiAdmin\Dto\Admin\GetReplicationHistoryDto;
use Wise\Core\ApiAdmin\Dto\Admin\ReplicationObjectHistoryDto;
use Wise\Core\ApiAdmin\Dto\Admin\ReplicationRequestHistoryDto;
use Wise\Core\ApiAdmin\ServiceInterface\Admin\ReplicationHistoryServiceInterface;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\Interfaces\Admin\ListReplicationHistoryServiceInterface;
use Wise\Core\Service\Interfaces\Admin\ListReplicationObjectHistoryServiceInterface;

class ReplicationHistoryService implements ReplicationHistoryServiceInterface
{
    public function __construct(
        protected readonly ListReplicationHistoryServiceInterface $listReplicationHistoryService,
        protected readonly ListReplicationObjectHistoryServiceInterface $listReplicationObjectHistoryService
    ) {
    }

    public function get(ParameterBag $parameters): JsonResponse
    {
        $responseDto = new GetReplicationHistoryDto();

        $this->fillReplicationRequests($responseDto, $parameters);

        if(!is_null($responseDto->getObjects()) && count($responseDto->getObjects()) > 0) {
            $this->fillReplicationObjects($responseDto, $parameters);
        }

        return new JsonResponse($responseDto->resolveArrayData());
    }

    protected function fillReplicationRequests(GetReplicationHistoryDto $responseDto, ParameterBag $parameters)
    {
        $listParameters = (new CommonListParams());

        $queryFilters = [];
        if ($parameters->has('requestId')) {
            $queryFilters[] = new QueryFilter('id', $parameters->get('requestId'));
        }

        if ($parameters->has('responseStatus')) {
            $queryFilters[] = new QueryFilter('responseStatus', $parameters->get('responseStatus'));
        }

        if ($parameters->has('requestUuid')) {
            $queryFilters[] = new QueryFilter('uuid', $parameters->get('requestUuid'));
        }

        if ($parameters->has('method')) {
            $queryFilters[] = new QueryFilter('method', $parameters->get('method'));
        }

        if ($parameters->has('limit')) {
            $listParameters->setLimit((int)$parameters->get('limit'));
        }

        if ($parameters->has('page')) {
            $listParameters->setPage((int)$parameters->get('page'));
        }

        if ($parameters->has('changeDateFrom')) {
            $queryFilters[] = new QueryFilter(
                'sysUpdateDate',
                $parameters->get('changeDateFrom'),
                Queryfilter::COMPARATOR_GREATER_THAN_OR_EQUAL
            );
        }

        if ($parameters->has('changeDateTo')) {
            $queryFilters[] = new QueryFilter(
                'sysUpdateDate',
                $parameters->get('changeDateTo'),
                Queryfilter::COMPARATOR_LESS_THAN_OR_EQUAL
            );
        }

        $listParameters->setFilters($queryFilters);

        $fields = [
            'dateUpdate' => 'sysUpdateDate',
        ];

        $fields = (new ReplicationRequestHistoryDto())->mergeWithMappedFields($fields);

        $listParameters->setFields($fields);
        $result = ($this->listReplicationHistoryService)($listParameters);

        foreach ($result as $object) {
            if (isset($object['sysUpdateDate'])) {
                $object['sysUpdateDate'] = $object['sysUpdateDate']->format('Y-m-d H:i:s');
            }

            $replicationRequest = (new ReplicationRequestHistoryDto())->fillWithObjectMappedFields(
                (array)$object,
                $fields
            );

            $responseDto->addObject($replicationRequest);
        }
    }

    protected function fillReplicationObjects(
        GetReplicationHistoryDto $resultDto,
        ParameterBag $parameters
    ) {
        if (!$parameters->has('fetchObjects') || $parameters->get('fetchObjects') !== 'true') {
            return;
        }

        $replicationRequestsIds = [];

        foreach ($resultDto->getObjects() as $replicationRequest) {
            $replicationRequestsIds[] = $replicationRequest->getId();
        }

        $listParameters = (new CommonListParams());

        $queryFilters[] = new QueryFilter('idRequest', $replicationRequestsIds, QueryFilter::COMPARATOR_IN);
        $listParameters->setfilters($queryFilters);

        $fields = [
            'dateUpdate' => 'sysUpdateDate',
        ];

        $fields = (new ReplicationObjectHistoryDto())->mergeWithMappedFields($fields);
        $listParameters->setFields($fields);

        $result = ($this->listReplicationObjectHistoryService)($listParameters);

        foreach ($resultDto->getObjects() as $replicationRequest) {
            /** @var ReplicationObjectHistoryDto $replicationObject */
            foreach ($result as $replicationObjectData) {
                if ($replicationObjectData['idRequest'] === $replicationRequest->getId()) {
                    if (isset($replicationObjectData['sysUpdateDate'])) {
                        $replicationObjectData['sysUpdateDate'] = $replicationObjectData['sysUpdateDate']->format('Y-m-d H:i:s');
                    }
                    $replicationObject = (new ReplicationObjectHistoryDto())->fillWithObjectMappedFields(
                        $replicationObjectData,
                        $fields
                    );
                    $replicationRequest->addObject($replicationObject);
                }
            }
        }
    }
}
