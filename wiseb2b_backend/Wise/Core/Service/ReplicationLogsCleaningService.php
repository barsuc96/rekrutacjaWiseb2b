<?php

namespace Wise\Core\Service;

use Wise\Core\Repository\Doctrine\ReplicationObjectRepositoryInterface;
use Wise\Core\Repository\Doctrine\ReplicationRequestRepositoryInterface;
use Wise\Core\Service\Interfaces\ReplicationLogsCleaningServiceInterface;
/**
 * Serwis naprawiający problemy z integracją
 */
class ReplicationLogsCleaningService implements ReplicationLogsCleaningServiceInterface
{

    public function __construct(
        private readonly ReplicationRequestRepositoryInterface $replicationRequestRepository,
        private readonly ReplicationObjectRepositoryInterface $replicationObjectRepository,
    ){}



    public function __invoke(string $endpoint, ?string $method, int $keepHours ): void
    {
        $this->replicationRequestRepository->cleanUpLogs($endpoint, $method, $keepHours);
        $this->replicationObjectRepository->removeOrphans();
    }
}
