<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class GetUserResponseDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Identyfikator użytkownika',
        example: 1,
    )]
    protected int $id;

    #[OA\Property(
        description: 'Imię użytkownika',
        example: 'Jan',
    )]
    protected string $firstName;

    #[OA\Property(
        description: 'Nazwisko użytkownika',
        example: 'Nowak',
    )]
    protected string $lastName;

    #[OA\Property(
        description: 'Ilość ofert',
        example: 4,
    )]
    protected int $totalOffers;

    #[OA\Property(
        description: 'Ilość zamówień',
        example: 5,
    )]
    protected int $totalOrders;

    #[OA\Property(
        description: 'Adres email użytkownika',
        example: 'example@example.com',
    )]
    protected string $email;

    #[OA\Property(
        description: 'Status użytkownika',
        example: 1,
    )]
    protected int $status;

    #[OA\Property(
        description: 'Id roli użytkownika',
        example: 2,
    )]
    protected int $roleId;

    #[OA\Property(
        description: 'Rola użytkownika',
        example: 'ROLE_USER',
    )]
    protected string $role;

    #[OA\Property(
        description: 'Informacja czy można modyfikować użytkownika',
        example: true,
    )]
    protected bool $canModifyUser = true;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getTotalOffers(): int
    {
        return $this->totalOffers;
    }

    public function setTotalOffers(int $totalOffers): self
    {
        $this->totalOffers = $totalOffers;

        return $this;
    }

    public function getTotalOrders(): int
    {
        return $this->totalOrders;
    }

    public function setTotalOrders(int $totalOrders): self
    {
        $this->totalOrders = $totalOrders;

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

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCanModifyUser(): bool
    {
        return $this->canModifyUser;
    }

    public function setCanModifyUser(bool $canModifyUser): self
    {
        $this->canModifyUser = $canModifyUser;

        return $this;
    }

    public function getRoleId(): int
    {
        return $this->roleId;
    }

    public function setRoleId(int $roleId): void
    {
        $this->roleId = $roleId;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }
}
