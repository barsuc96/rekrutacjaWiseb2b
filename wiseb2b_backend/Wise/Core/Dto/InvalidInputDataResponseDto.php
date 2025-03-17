<?php

declare(strict_types=1);

namespace Wise\Core\Dto;

use OpenApi\Attributes as OA;

class InvalidInputDataResponseDto extends AbstractDto
{
    #[OA\Property(
        description: 'Komunikat o błędzie',
        example: 'Invalid input data',
    )]
    protected string $message = 'Invalid input data';

    /** @var null|string[] $fields */
    protected ?array $fields = null;

    public function __construct(string $message, ?array $fields = null) {
        $this->message = $message;
        $this->fields = $fields;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getFields(): ?array
    {
        return $this->fields;
    }

    public function setFields(?array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }
}
