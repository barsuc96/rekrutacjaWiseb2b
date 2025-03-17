<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class StoreDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Identyfikator sklepu',
        example: 1,
    )]
    protected ?int $id;

    #[OA\Property(
        description: 'Symbol sklepu',
        example: 'WEBSITE_1',
    )]
    protected ?string $symbol;

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
