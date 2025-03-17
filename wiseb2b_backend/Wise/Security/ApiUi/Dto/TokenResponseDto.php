<?php

namespace Wise\Security\ApiUi\Dto;

use OpenApi\Attributes as OA;

class TokenResponseDto
{
    #[OA\Property(
        description: 'Token do uwierzytelniania użytkownika w endpointach',
    )]
    protected string $token;

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): TokenResponseDto
    {
        $this->token = $token;
        return $this;
    }
}
