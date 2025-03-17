<?php

declare(strict_types=1);

namespace Wise\Core\Service;

class OverLoginUserParams extends CommonListParams
{
    protected ?int $userId = null;

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
