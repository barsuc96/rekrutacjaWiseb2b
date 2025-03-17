<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client\Command;

class ViesRecalculateForClientCommand
{
    public function __construct(
        private int $clientId
    ){}

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function setClientId(int $clientId): void
    {
        $this->clientId = $clientId;
    }
}
