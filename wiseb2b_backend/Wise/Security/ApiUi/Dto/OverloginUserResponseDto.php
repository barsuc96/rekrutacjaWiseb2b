<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class OverloginUserResponseDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Identyfikator użytkownika',
        example: 5,
    )]
    protected int $id;

    #[OA\Property(
        description: 'Nazwa użytkownika',
        example: 'Jan Kowalski',
    )]
    protected string $name;

    #[OA\Property(
        description: 'Login użytkownika',
        example: 'biuro@sente.pl',
    )]
    protected string $login;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

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

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }
}
