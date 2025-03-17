<?php

namespace Wise\Receiver\ApiUi\Dto;

use Wise\Core\ApiUi\Dto\CommonGetResponseDto;

class GetReceiversCountriesResponseDto extends CommonGetResponseDto
{
    /** @var ReceiverCountryDto[] */
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
