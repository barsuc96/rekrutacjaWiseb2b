<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Dto\Traders;

use OpenApi\Attributes as OA;
use Wise\Core\ApiAdmin\Dto\CommonGetAdminApiDto;

class GetTradersQueryParametersDto extends CommonGetAdminApiDto
{
    #[OA\Property(
        description: 'Zewnętrzne ID handlowca',
        example: 'TRADER-123',
    )]
    protected string $id;

    #[OA\Property(
        description: 'Wewnętrzne ID handlowca',
        example: 1,
    )]
    protected int $internalId;

    #[OA\Property(
        description: 'Czy domyślny?',
        example: true,
    )]
    protected bool $isDefault;

    #[OA\Property(
        description: 'Czy aktywny?',
        example: true,
    )]
    protected bool $isActive;

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

    public function getIsDefault(): bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;

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
}
