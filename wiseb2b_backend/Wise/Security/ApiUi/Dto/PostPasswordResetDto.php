<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractDto;

class PostPasswordResetDto extends AbstractDto
{
    #[OA\Property(
        description: 'Token który został wysłany emailem do użytkownika',
        example: '1.1676450960.6f0deb8e0a9b2d73ce5b914c34f26f324bc9b47f',
    )]
    protected string $token;

    #[OA\Property(
        description: 'Nowe hasło do ustawenia',
        example: 'przykladowehaslo123',
    )]
    protected string $password;

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
