<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto\PanelManagement;

use Wise\Core\Dto\AbstractResponseDto;

class GetPanelManagementClientsResponseDto extends AbstractResponseDto
{
    /** @var GetPanelManagementClientResponseDto[] */
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
