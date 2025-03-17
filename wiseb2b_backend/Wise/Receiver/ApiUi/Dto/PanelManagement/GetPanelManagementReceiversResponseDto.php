<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiUi\Dto\PanelManagement;

use Wise\Core\Dto\AbstractResponseDto;

class GetPanelManagementReceiversResponseDto extends AbstractResponseDto
{
    /** @var GetPanelManagementReceiverResponseDto[] */
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
