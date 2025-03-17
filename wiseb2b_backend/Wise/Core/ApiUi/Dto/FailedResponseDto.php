<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class FailedResponseDto extends AbstractResponseDto
{
    public function __construct(
        /** @var string[] $data */
        #[OA\Property(description: 'Treść błędu', example: 'Wystąpił problem z połączeniem do bazy danych.')]
        protected ?array $data = null,
    ) {}

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }
}
