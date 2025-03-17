<?php

declare(strict_types=1);

namespace Wise\Client\Domain\ClientDocument;

use Wise\Core\Entity\AbstractEntity;

class ClientDocument extends AbstractEntity
{
    protected ?int $clientId = null;
    protected ?int $clientDocumentDefinitionId = null;

    protected ?string $symbol = null;

    protected ?string $path = null;

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getClientDocumentDefinitionId(): ?int
    {
        return $this->clientDocumentDefinitionId;
    }

    public function setClientDocumentDefinitionId(?int $clientDocumentDefinitionId): self
    {
        $this->clientDocumentDefinitionId = $clientDocumentDefinitionId;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(?string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }
}
