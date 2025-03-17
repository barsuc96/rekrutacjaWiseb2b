<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto\PanelManagement;

use Wise\Core\Dto\AbstractResponseDto;

class GetPanelManagementClientsGroupsResponseDto extends AbstractResponseDto
{
    /** @var GetPanelManagementClientsGroupResponseDto[] */
    protected ?array $objects;

    public function getObjects(): ?array
    {
        return $this->objects;
    }

    public function setObjects(?array $objects): self
    {
        $this->objects = $objects;

        return $this;
    }
}
