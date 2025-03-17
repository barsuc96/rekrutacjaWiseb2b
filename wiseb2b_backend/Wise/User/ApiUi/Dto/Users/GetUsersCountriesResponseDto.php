<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use Wise\Core\ApiUi\Dto\CommonGetResponseDto;

class GetUsersCountriesResponseDto extends CommonGetResponseDto
{
    /** @var UsersCountryDto[] */
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
