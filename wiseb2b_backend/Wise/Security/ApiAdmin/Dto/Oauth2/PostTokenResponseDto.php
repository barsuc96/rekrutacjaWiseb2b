<?php

declare(strict_types=1);

namespace Wise\Security\ApiAdmin\Dto\Oauth2;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractDto;

class PostTokenResponseDto extends AbstractDto
{
    #[OA\Property(
        description: 'Typ tokenu',
        example: 'Bearer',
    )]
    private ?string $tokenType;

    #[OA\Property(
        description: 'Czas życia w sekundach',
        example: 3600,
    )]
    private ?int $expiresIn;

    #[OA\Property(
        description: 'Token ',
        example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJkZWE5ZDVlZmFhMGMzNGRkNjc0MDA4NzRmM2UxM2U3YSIsImp0aSI6IjQxNTYwMWY3NDJlZWU0Yjk4MGVlODA0ZjUwMDQyYWY0MzQ4YzdjMTYxNzNjNzQ0MDZiOGQ4MTZmOWYyZTM0NTY3YTUxOTM3ZmVmMmQzZWY3IiwiaWF0IjoxNjc2NDkxOTk2Ljg4MDM0OSwibmJmIjoxNjc2NDkxOTk2Ljg4MDM1MiwiZXhwIjoxNjc2NDk1NTk2Ljg3NTI2Niwic3ViIjoiIiwic2NvcGVzIjpbXX0.HjZHU1Vj5RVoFgXAek9s0-V-Vot_jSXiVnIYOmnHxTenyrsLYRMwaycffkXf2PTWCiO0sY_bfrbSVN7Ft5ZlzMbkUewcUktmj7ir7fGPUzZL_9SqYFc-UTeNWz2YOA7c-Mfqy-5aGDe1yLV4RFPactOQtPUuM6DL1TbhNUDgE8Zg3UhlbAhzYI9bWWNcIvvpPcxPD1YpCuurPRab5QH0D4GxFIAI7hZLfRqKQIJ_HUuCShEid57jqAqRcEB22U7t_botBD2qBln-vi2pVU38jH70RwbHfRITuNjJUJRF0Efs-H2yuYH2qUTqa8RQz7dllI7-9TBI6A8vWq1DgV-OYQ',
    )]
    private ?string $accessToken;

    public function getTokenType(): ?string
    {
        return $this->tokenType;
    }

    public function setTokenType(?string $tokenType): self
    {
        $this->tokenType = $tokenType;

        return $this;
    }

    public function getExpiresIn(): ?int
    {
        return $this->expiresIn;
    }

    public function setExpiresIn(?int $expiresIn): self
    {
        $this->expiresIn = $expiresIn;

        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(?string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }
}
