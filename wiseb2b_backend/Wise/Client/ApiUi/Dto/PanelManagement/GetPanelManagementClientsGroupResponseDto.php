<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto\PanelManagement;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class GetPanelManagementClientsGroupResponseDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Id wewnętrzne',
        example: 1,
    )]
    protected ?int $id;

    #[OA\Property(
        description: 'Nazwa',
        example: 'Standardowa grupa klientów',
    )]
    protected ?string $name;

    #[OA\Property(
        description: 'Aktywność',
        example: true,
    )]
    protected ?bool $isActive;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
