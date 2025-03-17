<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiAdmin\Dto\Receivers;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractDto;

class DeleteReceiversByKeyAttributesDto extends AbstractDto
{
    #[OA\Property(
        description: 'Id zewnętrzne Receivers, nadane w ERP',
        example: 'XYZ-ASD-123',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Id zewnętrzne Receivers, może mieć maksymalnie 255 znaków",
    )]
    protected string $receiverId;

    public function getReceiverId(): string
    {
        return $this->receiverId;
    }

    public function setReceiverId(string $receiverId): self
    {
        $this->receiverId = $receiverId;

        return $this;
    }
}
