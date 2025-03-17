<?php

declare(strict_types=1);

namespace Wise\Core\Domain;

use Wise\Core\Entity\AbstractEntity;

/**
 * TODO: [ws] opis, nie mam pojęcia co to jest
 */
class SessionParam extends AbstractEntity
{
    protected string $sessionId;

    protected string $symbol;

    protected ?string $value = null;

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function setSessionId(string $sessionId): void
    {
        $this->sessionId = $sessionId;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): void
    {
        $this->symbol = $symbol;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }
}
