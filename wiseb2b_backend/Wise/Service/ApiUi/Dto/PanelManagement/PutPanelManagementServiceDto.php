<?php

namespace Wise\Service\ApiUi\Dto\PanelManagement;

use OpenApi\Attributes as OA;

class PutPanelManagementServiceDto extends PostPanelManagementServiceDto
{
    #[OA\Property(
        description: 'Identyfikator',
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
