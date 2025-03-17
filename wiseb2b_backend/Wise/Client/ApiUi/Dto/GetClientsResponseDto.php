<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto;

use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class GetClientsResponseDto extends CommonUiApiDto
{
    /** @var ClientResponseDto[] */
    protected ?array $items;

    public function getItems(): ?array
    {
        return $this->items;
    }

    public function setItems(?array $items): self
    {
        $this->items = $items;
        return $this;
    }
}
