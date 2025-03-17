<?php

namespace Wise\Agreement\ApiUi\Dto\ContractAgreement;

use Wise\Core\ApiUi\Dto\CommonParameters\CommonParametersDto;

class PostUserAgreesContractsDto extends CommonParametersDto
{

    /** @var PostUserAgreeContractDto[] */
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
