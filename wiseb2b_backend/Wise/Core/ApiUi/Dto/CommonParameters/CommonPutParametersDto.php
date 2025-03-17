<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Dto\CommonParameters;

use OpenApi\Attributes as OA;

class CommonPutParametersDto extends CommonParametersDto
{
    #[OA\Parameter(
        description: 'Identyfikator wewnętrzny',
        in: 'path',
        example: '3'
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
