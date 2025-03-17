<?php

namespace Wise\Client\ApiUi\Dto;

class ExpendedAddressDto extends AddressDto
{
    protected ?string $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
