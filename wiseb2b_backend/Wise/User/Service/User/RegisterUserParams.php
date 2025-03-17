<?php

declare(strict_types=1);

namespace Wise\User\Service\User;

use Wise\Core\Dto\CommonServiceDTO;

/**
 * Parametry rejestracji użytkownika
 */
class RegisterUserParams extends CommonServiceDTO
{
    /**
     * Identyfikator klienta. Gdy wartość jest null, tworzy nowego użytkownika w systemie wraz z nowym klientem. Jeśli ta wartość jest uzupełniona, tworzy użytkownika w systemie dla istniejącego klienta.
     * @var int|null
     */
    private ?int $clientId = null;

    /**
     * Jaka wartość isActive ma zostać ustawiona po utworzeniu użytkownika
     * @var bool|null
     */
    private ?bool $isActiveUserAfterCreated = false;

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getIsActiveUserAfterCreated(): ?bool
    {
        return $this->isActiveUserAfterCreated;
    }

    public function setIsActiveUserAfterCreated(?bool $isActiveUserAfterCreated): self
    {
        $this->isActiveUserAfterCreated = $isActiveUserAfterCreated;

        return $this;
    }
}
