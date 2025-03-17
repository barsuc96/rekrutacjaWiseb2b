<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Dto\Users;

use OpenApi\Attributes as OA;
use Wise\Core\ApiAdmin\Dto\CommonGetAdminApiDto;

class GetUsersQueryParametersDto extends CommonGetAdminApiDto
{
    #[OA\Property(
        description: 'Zewnętrzne ID',
        example: 'UUID-123',
    )]
    protected string $id;

    #[OA\Property(
        description: 'Wewnętrzne ID',
        example: 1,
    )]
    protected int $internalId;

    #[OA\Property(
        description: 'Zewnętrzne ID klienta',
        example: 'UUID-123',
    )]
    protected string $clientId;

    #[OA\Property(
        description: 'Wewnętrzne ID klienta',
        example: 1,
    )]
    protected int $clientInternalId;

    #[OA\Property(
        description: 'Zewnętrzne ID roli',
        example: 'UUID-123',
    )]
    protected string $roleId;

    #[OA\Property(
        description: 'Wewnętrzne ID roli',
        example: 1,
    )]
    protected int $roleInternalId;

    #[OA\Property(
        description: 'Zewnętrzne ID tradera',
        example: 'UUID-123',
    )]
        protected string $traderId;

    #[OA\Property(
        description: 'Wewnętrzne ID tradera',
        example: 1,
    )]
    protected int $traderInternalId;

    #[OA\Property(
        description: 'Czy użytkownik aktywny?',
        example: true,
    )]
    protected bool $isActive;

    #[OA\Property(
        description: 'Identyfikator sklepu',
        example: 1,
    )]
    protected ?int $storeId;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getInternalId(): int
    {
        return $this->internalId;
    }

    public function setInternalId(int $internalId): self
    {
        $this->internalId = $internalId;

        return $this;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getClientInternalId(): int
    {
        return $this->clientInternalId;
    }

    public function setClientInternalId(int $clientInternalId): self
    {
        $this->clientInternalId = $clientInternalId;

        return $this;
    }

    public function getRoleId(): string
    {
        return $this->roleId;
    }

    public function setRoleId(string $roleId): self
    {
        $this->roleId = $roleId;

        return $this;
    }

    public function getRoleInternalId(): int
    {
        return $this->roleInternalId;
    }

    public function setRoleInternalId(int $roleInternalId): self
    {
        $this->roleInternalId = $roleInternalId;

        return $this;
    }

    public function getTraderId(): string
    {
        return $this->traderId;
    }

    public function setTraderId(string $traderId): self
    {
        $this->traderId = $traderId;

        return $this;
    }

    public function getTraderInternalId(): int
    {
        return $this->traderInternalId;
    }

    public function setTraderInternalId(int $traderInternalId): self
    {
        $this->traderInternalId = $traderInternalId;

        return $this;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
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
