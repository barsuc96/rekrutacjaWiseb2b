<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use OpenApi\Attributes as OA;

class SalseSupervisorDto
{
    #[OA\Property(
        description: 'Nazwa opiekuna',
        example: 'Jan Nowak',
    )]
    protected string $name;

    #[OA\Property(
        description: 'Numer telefonu opiekuna',
        example: '+48777555777',
    )]
    protected string $phone;

    #[OA\Property(
        description: 'Adres e-mail opiekuna',
        example: 'example@example.com',
    )]
    protected string $email;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
