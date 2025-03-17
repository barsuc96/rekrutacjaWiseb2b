<?php

namespace Wise\User\ApiUi\Dto\PanelManagement\Users;

use Wise\Core\ApiUi\Dto\CommonParameters\CommonParametersDto;
use OpenApi\Attributes as OA;

class PostPanelManagementUserPasswordResetDto extends CommonParametersDto
{
    #[OA\Property(
        description: 'Id użytkownika',
        example: 4,
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

