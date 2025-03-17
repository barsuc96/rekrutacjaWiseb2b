<?php

declare(strict_types=1);

namespace Wise\User\Service\User;

use Wise\Core\Service\CommonDetailsParams;

class GetUserDetailsParams extends CommonDetailsParams
{
    public const ADDITIONAL_DATA_TYPES = [
    ];

    /**
     * Aktualnie zalogowany użytkownik
     */
    protected ?int $userId = null;

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;
        $this->setId($userId);

        return $this;
    }
}
