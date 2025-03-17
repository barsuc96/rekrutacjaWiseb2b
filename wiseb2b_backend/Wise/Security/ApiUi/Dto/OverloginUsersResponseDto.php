<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class OverloginUsersResponseDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Identyfikator użytkownika - clienta',
        example: 5,
    )]
    protected int $id;

    #[OA\Property(
        description: 'Nazwa użytkownika',
        example: 'Jan Kowalski',
    )]
    protected string $name;

    /** @var OverloginUserResponseDto[] */
    protected ?array $usersList = null;

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

    public function getUsersList(): ?array
    {
        return $this->usersList;
    }

    public function setUsersList(?array $usersList): self
    {
        $this->usersList = $usersList;

        return $this;
    }

    public function addUserToList(OverloginUserResponseDto $user): self
    {
        $this->usersList[] = $user;

        return $this;
    }
}
