<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\ApiUi\Dto\CommonGetUiApiDto;

class GetClientsQueryParametersDto extends CommonGetUiApiDto
{
    #[OA\Property(
        description: 'Filtrowanie na podstawie statusu',
        example: 1,
    )]
    protected ?int $status;

    #[OA\Property(
        description: 'Filtrowanie statusie aktywacji',
        example: true,
    )]
    protected ?bool $isActive;

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

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
