<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractDto;

class PostUserRegisterAgreementsRequestDto extends AbstractDto
{
    #[OA\Property(
        description: 'Typ zgody',
        example: 'RULES',
    )]
    protected ?string $type;

    #[OA\Property(
        description: 'Czy wyrażono zgodę',
        example: true,
    )]
    protected ?bool $accepted;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAccepted(): ?bool
    {
        return $this->accepted;
    }

    public function setAccepted(?bool $accepted): self
    {
        $this->accepted = $accepted;

        return $this;
    }
}
