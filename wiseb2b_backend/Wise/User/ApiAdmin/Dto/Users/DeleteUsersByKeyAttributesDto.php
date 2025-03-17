<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Dto\Users;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractDto;

class DeleteUsersByKeyAttributesDto extends AbstractDto
{
    #[OA\Property(
        description: 'Id zewnętrzne Users, nadane w ERP',
        example: 'XYZ-ASD-123',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Id zewnętrzne Users, może mieć maksymalnie 255 znaków",
    )]
    protected string $userId;

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
