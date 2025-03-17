<?php

namespace Wise\User\ApiUi\Dto\Contract;

use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class PostUserContractsTogglesDto extends CommonUiApiDto
{

    /** @var PostUserContractTogglesDto[] */
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

