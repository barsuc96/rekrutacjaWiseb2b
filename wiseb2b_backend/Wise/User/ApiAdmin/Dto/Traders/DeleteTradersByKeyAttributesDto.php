<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Dto\Traders;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractDto;

class DeleteTradersByKeyAttributesDto extends AbstractDto
{
    #[OA\Property(
        description: 'Id zewnętrzne Traders, nadane w ERP',
        example: 'XYZ-ASD-123',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Id zewnętrzne Traders, może mieć maksymalnie 255 znaków",
    )]
    protected string $traderId;

    public function getTraderId(): string
    {
        return $this->traderId;
    }

    public function setTraderId(string $traderId): self
    {
        $this->traderId = $traderId;

        return $this;
    }
}
