<?php

declare(strict_types=1);

namespace Wise\Core\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\ApiAdmin\Enum\ResponseStatusEnum;

class CommonApiExceptionResponseDto extends AbstractDto
{
    public function __construct(
        #[OA\Property(
            description: 'Komunikat o błędzie',
            example: 'Validation message.',
        )]
        protected string $message = 'Validation message.',
        #[OA\Property(
            description: 'Status',
            example: '0',
        )]
        protected int $status = 0
    ) {
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

    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }
}
