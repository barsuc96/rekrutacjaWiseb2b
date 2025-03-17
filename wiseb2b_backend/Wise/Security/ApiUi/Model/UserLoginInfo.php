<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Model;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserLoginInfo implements UserInterface, PasswordAuthenticatedUserInterface, UserLoginInfoInterface
{
    public function __construct(
        protected ?int $id = null,
        protected ?int $clientId = null,
        protected ?string $login = null,
        protected ?string $idExternal = null,
        protected ?array $roles = null,
        protected ?string $password = null,
        protected ?string $salt = null,
        protected ?string $currentSessionId = null,
        protected ?bool $overlogged = false,
        protected ?int $storeId = null
    ) {
    }

    public function getCurrentSessionId(): ?string
    {
        return $this->currentSessionId;
    }

    public function setCurrentSessionId(?string $currentSessionId): void
    {
        $this->currentSessionId = $currentSessionId;
    }

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getIdExternal(): ?string
    {
        return $this->idExternal;
    }

    public function getRoles(): array
    {
        return $this->roles ?? [];
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function eraseCredentials(): void
    {
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier() instead
     */
    #[Pure]
    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    public function getUserIdentifier(): string
    {
        return $this->login;
    }

    public function getOverlogged(): ?bool
    {
        return $this->overlogged;
    }

    public function setOverlogged(?bool $overlogged): self
    {
        $this->overlogged = $overlogged;

        return $this;
    }

    public function isOverlogged(): ?bool
    {
        return $this->overlogged;
    }

    public function getStoreId(): ?int
    {
        return $this->storeId;
    }

    public function setStoreId(?int $storeId): self
    {
        $this->storeId = $storeId;

        return $this;
    }
}
