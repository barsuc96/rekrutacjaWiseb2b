<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiUi\Dto;

use Wise\Core\ApiUi\Dto\CommonGetResponseDto;

class GetReceiversResponseDto extends CommonGetResponseDto
{
    /** @var ReceiversResponseDto[] */
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
