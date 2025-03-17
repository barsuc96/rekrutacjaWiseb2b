<?php

namespace Wise\Agreement\ApiUi\Dto\ContractAgreement;

use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class PostUserContractsToggleDto extends CommonUiApiDto
{

    /** @var PostUserContractToggleDto[] */
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
