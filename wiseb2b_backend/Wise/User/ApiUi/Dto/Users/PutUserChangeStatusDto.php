<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractDto;

class PutUserChangeStatusDto extends AbstractDto
{
    #[OA\Property(
        description: 'Zmiana pola aktywny',
        example: true,
    )]
    protected bool $isActive;

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
