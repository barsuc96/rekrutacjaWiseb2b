<?php

declare(strict_types=1);


namespace Wise\Client\Service\Client;

use Wise\Core\Service\CommonDetailsParams;

class GetClientDetailsParams extends CommonDetailsParams
{
    public const ADDITIONAL_DATA_TYPES = [
        'registerAddress'
    ];

    protected ?int $clientId = null;

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;
        $this->setId($clientId);

        return $this;
    }
}
