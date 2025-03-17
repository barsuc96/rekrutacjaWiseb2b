<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Dto;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;

class CommonUiApiDeleteParametersDto extends CommonUiApiParametersDto
{
    #[OA\Path(
        description: 'Identyfikator encji',
        example: 1,
    )]
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
