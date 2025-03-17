<?php

namespace Wise\Client\Domain\ClientDocumentDefinition;

use Wise\Core\Entity\AbstractEntity;

class ClientDocumentDefinition extends AbstractEntity
{
    protected ?string $idExternal = null;

    protected ?string $name = null;

    protected ?string $description = null;

    public function getIdExternal(): ?string
    {
        return $this->idExternal;
    }

    public function setIdExternal(?string $idExternal): self
    {
        $this->idExternal = $idExternal;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
