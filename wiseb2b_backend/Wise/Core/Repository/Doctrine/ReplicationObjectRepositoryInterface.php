<?php

namespace Wise\Core\Repository\Doctrine;

interface ReplicationObjectRepositoryInterface
{
    public function getStatistics(\DateTime $startDate, \DateTime $endDate, ?int $status = null): array;

    public function removeOrphans(): void;
}
