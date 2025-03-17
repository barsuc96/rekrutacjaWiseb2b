<?php

namespace Wise\Core\ApiAdmin\Service\Async\Handler;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Wise\Core\ApiAdmin\Service\Async\Command\PerformAsyncRequestCommand;
use Wise\Core\ApiAdmin\ServiceInterface\Admin\ReplicationRequestRetryServiceInterface;

#[AsMessageHandler]
class PerformAsyncRequestHandler
{
    public function __construct(
        private readonly ReplicationRequestRetryServiceInterface $retryService
    ){}

    public function __invoke(PerformAsyncRequestCommand $performAsyncRequestCommand): void
    {
        $this->retryService->retry($performAsyncRequestCommand->getRequestUuid());
    }
}
