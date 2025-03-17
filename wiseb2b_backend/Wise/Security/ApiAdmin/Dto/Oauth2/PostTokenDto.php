<?php

declare(strict_types=1);

namespace Wise\Security\ApiAdmin\Dto\Oauth2;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractDto;

class PostTokenDto extends AbstractDto
{
    #[OA\Property(
        description: 'identifier',
        example: '193379926627ca33f84c8d906ac67612',

    )]
    private ?string $clientId;

    #[OA\Property(
        description: 'secret',
        example: '193379926627ca33f84c8d906ac676126c291100dd9fa350e721e89e68e3361c',
    )]
    private ?string $clientSecret;

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
