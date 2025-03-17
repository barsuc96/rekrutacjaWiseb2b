<?php

declare(strict_types=1);

namespace Wise\Core\Api\Dto;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiUi\Dto\CommonParameters\CommonParametersDto;

class CommonCommandParametersDto extends CommonParametersDto
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
