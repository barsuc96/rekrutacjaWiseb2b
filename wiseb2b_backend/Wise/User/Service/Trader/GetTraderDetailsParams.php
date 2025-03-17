<?php

declare(strict_types=1);

namespace Wise\User\Service\Trader;

use Wise\Core\Dto\AbstractGetEntityDetailsParams;

class GetTraderDetailsParams extends AbstractGetEntityDetailsParams
{
    /**
     * Id tradera którego danych chcemy pobrać
     */
    protected ?int $traderId = null;

    public function getTraderId(): ?int
    {
        return $this->traderId;
    }

    public function setTraderId(?int $traderId): self
    {
        $this->traderId = $traderId;

        return $this;
    }
}
