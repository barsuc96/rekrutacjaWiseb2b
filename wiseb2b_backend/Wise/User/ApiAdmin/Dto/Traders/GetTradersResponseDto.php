<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Dto\Traders;

use Wise\Core\ApiAdmin\Dto\CommonResponseDto;

class GetTradersResponseDto extends CommonResponseDto
{
    /** @var GetTraderResponseDto[] */
    protected ?array $objects;

    public function getObjects(): ?array
    {
        return $this->objects;
    }

    public function setObjects(?array $objects): GetTradersResponseDto
    {
        $this->objects = $objects;

        return $this;
    }
}
