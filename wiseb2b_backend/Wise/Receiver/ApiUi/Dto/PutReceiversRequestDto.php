<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractDto;

class PutReceiversRequestDto extends AbstractDto
{
    #[OA\Parameter(
        description: 'ID odbiorcy',
        in: 'path',
        example: 1
    )]
    protected int $receiverId;

    #[Assert\NotBlank(
        message: "Ta wartość nie może być pusta."
    )]
    #[Assert\Length(
        max: 60,
        maxMessage: "Pole może zawierać maksymalnie {{ limit }} znaków."
    )]
    #[OA\Property(
        description: 'Nazwa odbiorcy',
        example: 'Quattro Forum',
    )]
    protected ?string $name;

    #[OA\Property(
        description: 'Typ odbiorcy',
        example: 'primary',
    )]
    protected ?string $type;

    #[Assert\NotBlank(
        message: "Ta wartość nie może być pusta."
    )]
    #[Assert\Length(
        max: 60,
        maxMessage: "Pole może zawierać maksymalnie {{ limit }} znaków."
    )]
    #[OA\Property(
        description: 'Imię odbiorcy',
        example: 'Adam',
    )]
    protected ?string $firstName;

    #[Assert\NotBlank(
        message: "Ta wartość nie może być pusta."
    )]
    #[Assert\Length(
        max: 60,
        maxMessage: "Pole może zawierać maksymalnie {{ limit }} znaków."
    )]
    #[OA\Property(
        description: 'Nazwisko odbiorcy',
        example: 'Kowalski',
    )]
    protected ?string $lastName;

    #[Assert\NotBlank(
        message: "Ta wartość nie może być pusta."
    )]
    #[Assert\Length(
        max: 60,
        maxMessage: "Pole może zawierać maksymalnie {{ limit }} znaków."
    )]
    #[Assert\Email(
        message: "Nieprawidłowy adres email."
    )]
    #[OA\Property(
        description: 'Adres e-mail odbiorcy',
        example: 'dkowalczyk@sente.pl',
    )]
    protected ?string $email;

    #[Assert\NotBlank(
        message: "Ta wartość nie może być pusta."
    )]
    #[Assert\Length(
        max: 60,
        maxMessage: "Pole może zawierać maksymalnie {{ limit }} znaków."
    )]
    #[OA\Property(
        description: 'Numer telefonu odbiorcy',
        example: '123456789',
    )]
    protected ?string $phone;

    #[OA\Property(
        description: 'ID klienta nadawane przez system ERP',
        example: 1,
    )]
    protected ?int $clientId;

    protected ?PutAddressDto $address;

    public function getReceiverId(): int
    {
        return $this->receiverId;
    }

    public function setReceiverId(int $receiverId): self
    {
        $this->receiverId = $receiverId;

        return $this;
    }

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?PutAddressDto
    {
        return $this->address;
    }

    public function setAddress(?PutAddressDto $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }
}
