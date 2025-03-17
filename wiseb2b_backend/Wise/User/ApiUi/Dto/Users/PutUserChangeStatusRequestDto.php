<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractDto;

class PutUserChangeStatusRequestDto extends AbstractDto
{
    #[OA\Parameter(description: 'ID Użytkownika', in: 'path', example: 66)]
    protected int $userId;


    #[OA\Property(description: 'Status użytkownika', example: true)]
    protected bool $isActive;

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }


}
