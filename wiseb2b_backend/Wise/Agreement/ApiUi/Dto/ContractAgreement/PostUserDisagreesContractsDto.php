<?php

namespace Wise\Agreement\ApiUi\Dto\ContractAgreement;

use Wise\Core\ApiUi\Dto\CommonParameters\CommonParametersDto;

class PostUserDisagreesContractsDto extends CommonParametersDto
{

    /** @var PostUserDisagreeContractDto[] */
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
