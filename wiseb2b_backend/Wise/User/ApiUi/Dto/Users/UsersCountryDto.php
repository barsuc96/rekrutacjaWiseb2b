<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class UsersCountryDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Kod kraju',
        example: 'DE',
    )]
    protected string $code;

    #[OA\Property(
        description: 'Nazwa kraju',
        example: 'Niemcy',
    )]
    protected string $name;

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
