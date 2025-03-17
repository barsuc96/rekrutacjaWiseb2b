<?php

namespace Wise\Client\ApiUi\Dto\PanelManagement;

use Wise\Core\ApiUi\Dto\CommonDictionaryElementResponseDto;
use Wise\Core\Dto\AbstractResponseDto;

class GetPanelManagementClientsGroupsDictionaryResponseDto extends AbstractResponseDto
{
    /** @var CommonDictionaryElementResponseDto[] */
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
