<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\ApiUi\Dto\CommonPostUiApiDto;

class LoginDto extends CommonPostUiApiDto
{
    #[OA\Property(
        description: 'Login użytkownika',
        example: 'biuro@sente.pl',
    )]
    protected string $username;

    #[OA\Property(
        description: 'Hasło użytkownika',
        example: 'przykladowehaslo',
    )]
    protected string $password;

    #[OA\Property(
        description: 'Identifier',
        example: 'ff65a8109ad27bggggbe036d08b7abb9',
    )]
    private ?string $clientId;

    #[OA\Property(
        description: 'Secret',
        example: '6bgggaa06d4b9437b42c51e5ee57092a91b933450d1ce3d6d087b0855130df5b8cc188968aa357b355dfe4755c95e53cd0ea3b85ae47162e0637816736202b03',
    )]
    private ?string $clientSecret;

    //#[OA\Property(
    //    description: 'Grant type',
    //    example: 'password',
    //)]
    //private ?string $grantType;
    //
    //public function getGrantType(): ?string
    //{
    //    return $this->grantType;
    //}
    //
    //public function setGrantType(?string $grantType): self
    //{
    //    $this->grantType = $grantType;
    //
    //    return $this;
    //}

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): LoginDto
    {
        $this->password = $password;

        return $this;
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function setClientId(?string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getClientSecret(): ?string
    {
        return $this->clientSecret;
    }

    public function setClientSecret(?string $clientSecret): self
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }
}
