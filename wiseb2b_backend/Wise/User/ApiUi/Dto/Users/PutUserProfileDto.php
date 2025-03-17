<?php

namespace Wise\User\ApiUi\Dto\Users;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractDto;

class PutUserProfileDto extends AbstractDto
{

    #[OA\Property(
        description: 'Identyfikator użytkownika',
        example: 12,
    )]
    protected int $userId;

    #[OA\Property(
        description: 'Imię',
        example: 'Jan',
    )]
    protected string $firstName;

    #[OA\Property(
        description: 'Nazwisko',
        example: 'Nowak',
    )]
    protected string $lastName;

    #[OA\Property(
        description: 'Adres email użytkownika',
        example: 'biuro@sente.pl',
    )]
    protected string $email;


    #[OA\Property(
        description: 'Dane klienta',
    )]
    protected CustomerDto $customer;

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCustomer(): CustomerDto
    {
        return $this->customer;
    }

    public function setCustomer(CustomerDto $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}