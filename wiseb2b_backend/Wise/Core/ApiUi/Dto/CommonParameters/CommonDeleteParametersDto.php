<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Dto\CommonParameters;

use OpenApi\Attributes as OA;

class CommonDeleteParametersDto extends CommonParametersDto
{
    #[OA\Parameter(description: 'Identyfikator encji', in: 'path', example: 1)]
    protected string $id;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }
}
