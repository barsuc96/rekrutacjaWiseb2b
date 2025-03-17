<?php

namespace Wise\Client\ApiUi\Dto;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;

class PutClientsRequestDto extends PostClientsRequestDto
{
    #[OA\Path(
        description: 'Identyfikator klienta',
        example: '2'
    )]
    protected int $id;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
