<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Dto\Clients;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractDto;

class DeleteClientsByKeyAttributesDto extends AbstractDto
{
    #[OA\Property(
        description: 'Id zewnętrzne Clients, nadane w ERP',
        example: 'XYZ-ASD-123',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Id zewnętrzne Clients, może mieć maksymalnie 255 znaków",
    )]
    protected string $clientId;

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }
}
