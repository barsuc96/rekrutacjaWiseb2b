<?php

declare(strict_types=1);

namespace Wise\Core\Dto;

use OpenApi\Attributes as OA;

class UnauthorizedResponseDto extends AbstractDto
{
    public function __construct(
        #[OA\Property(
            description: 'Komunikat o błędzie',
            example: 'Invalid token',
        )]
        protected string $message = 'Invalid token.',
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
}
