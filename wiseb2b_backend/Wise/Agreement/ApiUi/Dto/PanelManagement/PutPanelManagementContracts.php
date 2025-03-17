<?php

namespace Wise\Agreement\ApiUi\Dto\PanelManagement;

use OpenApi\Attributes as OA;

class PutPanelManagementContracts extends PostPanelManagementContractsDto
{
    #[OA\Property(
        description: 'Id umowy',
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
