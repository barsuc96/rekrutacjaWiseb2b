<?php

namespace Wise\Core\ApiAdmin\Service\Async\Command;

class PerformAsyncRequestCommand
{
    public function __construct(
        private string $requestUuid
    ){}

    public function getRequestUuid(): string
    {
            return $this->requestUuid;
    }
}
