<?php

namespace Wise\User\ApiUi\Dto\PanelManagement\Users;

use OpenApi\Attributes as OA;

class PutPanelManagementUserDto extends PostPanelManagementUserDto
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
