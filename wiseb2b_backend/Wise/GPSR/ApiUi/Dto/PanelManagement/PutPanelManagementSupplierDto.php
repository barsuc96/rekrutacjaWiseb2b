<?php

namespace Wise\GPSR\ApiUi\Dto\PanelManagement;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;

class PutPanelManagementSupplierDto extends PostPanelManagementSupplierDto
{
    #[OA\Property(
        description: 'Identyfikator',
        example: 1,
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
