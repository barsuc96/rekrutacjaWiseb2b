<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client;

use Wise\Core\Dto\CommonServiceDTO;

class VerifyClientViesInformationServiceParams extends CommonServiceDTO
{
    private ?int $clientId = null;

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }
}
