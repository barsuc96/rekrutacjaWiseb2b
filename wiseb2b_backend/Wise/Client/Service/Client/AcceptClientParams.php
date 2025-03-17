<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client;

use Wise\Core\Dto\CommonModifyParams;

class AcceptClientParams extends CommonModifyParams
{
    private ?int $clientId = null;

    protected ?int $status = null;

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
