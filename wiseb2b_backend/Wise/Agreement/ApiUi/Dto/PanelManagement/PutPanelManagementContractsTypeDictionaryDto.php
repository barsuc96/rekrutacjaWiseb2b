<?php

namespace Wise\Agreement\ApiUi\Dto\PanelManagement;

use OpenApi\Attributes as OA;

class PutPanelManagementContractsTypeDictionaryDto extends PostPanelManagementContractsTypeDictionaryDto
{
    #[OA\Property(
        description: 'Id typu słownikowego umowy',
        example: 5,
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
