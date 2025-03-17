<?php

declare(strict_types=1);

namespace Wise\Service\ApiAdmin\Dto\Services;

use OpenApi\Attributes as OA;
use Wise\Core\ApiAdmin\Dto\CommonGetAdminApiDto;

class GetServicesQueryParametersDto extends CommonGetAdminApiDto
{
    #[OA\Property(
        description: 'ID zewnętrzne usługi',
        example: 'example',
    )]
    protected string $id;

    #[OA\Property(
        description: 'ID wewnętrzne usługi',
        example: 1,
    )]
    protected int $internalId;

    #[OA\Property(
        description: 'Typ usługi',
        example: 'delivery',
    )]
    protected string $type;

    #[OA\Property(
        description: 'Czy usługa aktywna?',
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

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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
