<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class GetUserProfileResponseDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Identyfikator użytkownika',
        example: 12,
    )]
    protected int $id;

    #[OA\Property(
        description: 'Czy wymusić zmianę hasła?',
        example: true,
    )]
    protected bool $changePassword;

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
        description: 'Zgody',
    )]
    /** @var AgreementsDto[] $agreements */
    protected array $agreements;

    #[OA\Property(
        description: 'Rola użytkownika',
        example: 'Admin',
    )]
    protected string $role;

    #[OA\Property(
        description: 'Czy dany użytowkownik jest "wlogowany", czy "przelogowany" (overlogged)',
        example: false,
    )]
    protected bool $overlogged;

    #[OA\Property(
        description: 'Opiekun sprzedaży',
    )]
    protected SalseSupervisorDto $saleSupervisor;

    #[OA\Property(
        description: 'Dane klienta',
    )]
    protected CustomerDto $customer;

    #[OA\Property(
        description: 'Informacje o zalogowanym użytkowniku',
    )]
    protected LoggedUserDto $loggedUser;

    #[OA\Property(
        description: 'Pole określa czy użytkownik musi zaakceptować wymagane zgody',
        example: true,
    )]
    protected bool $consentsRequired;

    #[OA\Property(
        description: 'Adres e-mail do kontaktu z właścicielem sklepu (wykorzystywany do wyświetlania na frontendzie)',
        example: 'kontakt@wiseb2b.eu',
    )]
    protected string $emailToContactOwnerStore;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getChangePassword(): bool
    {
        return $this->changePassword;
    }

    public function setChangePassword(bool $changePassword): self
    {
        $this->changePassword = $changePassword;

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

    public function getAgreements(): array
    {
        return $this->agreements;
    }

    public function setAgreements(array $agreements): self
    {
        $this->agreements = $agreements;

        return $this;
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

    public function getIsOverlogged(): bool
    {
        return $this->overlogged;
    }

    public function setOverlogged(bool $overlogged): self
    {
        $this->overlogged = $overlogged;

        return $this;
    }

    public function getSaleSupervisor(): SalseSupervisorDto
    {
        return $this->saleSupervisor;
    }

    public function setSaleSupervisor(SalseSupervisorDto $saleSupervisor): self
    {
        $this->saleSupervisor = $saleSupervisor;

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

    public function getIsConsentsRequired(): bool
    {
        return $this->consentsRequired;
    }

    public function setConsentsRequired(bool $consentsRequired): self
    {
        $this->consentsRequired = $consentsRequired;

        return $this;
    }

    public function getLoggedUser(): LoggedUserDto
    {
        return $this->loggedUser;
    }

    public function setLoggedUser(LoggedUserDto $loggedUser): self
    {
        $this->loggedUser = $loggedUser;

        return $this;
    }

    public function getEmailToContactOwnerStore(): string
    {
        return $this->emailToContactOwnerStore;
    }

    public function setEmailToContactOwnerStore(string $emailToContactOwnerStore): self
    {
        $this->emailToContactOwnerStore = $emailToContactOwnerStore;

        return $this;
    }
}
