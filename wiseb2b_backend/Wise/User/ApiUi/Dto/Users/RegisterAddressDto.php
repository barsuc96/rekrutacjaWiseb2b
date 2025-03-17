<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use Wise\Core\Validator\Constraints as WiseAssert;
use OpenApi\Attributes as OA;

class RegisterAddressDto extends AddressDto
{
    #[OA\Property(
        description: 'Nazwa odbiorcy/firmy',
        example: 'WiseB2B Sp. z o.o.',
    )]
    #[WiseAssert\NotBlank]
    protected ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
