<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractDto;

class PostOverLoginDto extends AbstractDto
{
    #[OA\Property(
        description: 'Id użytkownika, na którego nastąpi przelogowanie',
        example: '123',
    )]
    protected int $toSwitchUserId;

    /**
     * @return int
     */
    public function getToSwitchUserId(): int
    {
        return $this->toSwitchUserId;
    }

    /**
     * @param int $toSwitchUserId
     */
    public function setToSwitchUserId(int $toSwitchUserId): void
    {
        $this->toSwitchUserId = $toSwitchUserId;
    }
}
