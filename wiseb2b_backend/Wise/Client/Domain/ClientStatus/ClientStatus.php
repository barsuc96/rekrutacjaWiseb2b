<?php

declare(strict_types=1);

namespace Wise\Client\Domain\ClientStatus;

use Wise\Core\Model\AbstractModel;

class ClientStatus extends AbstractModel
{
    /**
     * Identyfikator Statusu
     * @var int|null
     */
    private ?int $id = null;

    /**
     * Symbol Statusu - NEW, ACTIVE, ARCHIVE
     * @var string|null
     */
    private ?string $symbol = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

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
}
