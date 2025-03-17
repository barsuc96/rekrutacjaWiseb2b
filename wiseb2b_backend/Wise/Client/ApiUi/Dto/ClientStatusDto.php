<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\Api\Dto\CommonParameterListTrait;
use Wise\Core\Dto\AbstractResponseDto;

class ClientStatusDto extends AbstractResponseDto
{
    use CommonParameterListTrait;

    #[OA\Property(
        description: 'Status klienta',
        example: 0,
    )]
    protected int $status;

    #[OA\Property(
        description: 'Status klienta (symbol)',
        example: 'NEW',
    )]
    protected string $statusSymbol;

    #[OA\Property(
        description: 'Status klienta - do wyświetlenia',
        example: 'Do weryfikacji',
    )]
    protected string $statusFormatted;

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatusSymbol(): string
    {
        return $this->statusSymbol;
    }

    public function setStatusSymbol(string $statusSymbol): self
    {
        $this->statusSymbol = $statusSymbol;

        return $this;
    }

    public function getStatusFormatted(): string
    {
        return $this->statusFormatted;
    }

    public function setStatusFormatted(string $statusFormatted): self
    {
        $this->statusFormatted = $statusFormatted;

        return $this;
    }
}
