<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiAdmin\Dto\Receivers;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Dto\AbstractDto;

class PutReceiverDto extends AbstractDto
{
    #[OA\Property(
        description: 'ID nadawane przez system ERP',
        example: '1',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Id zewnętrzne odbiorcy, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected string $id;

    #[OA\Property(
        description: 'ID wewnętrzne systemu B2B. Można używać zamiennie z id (o ile jest znane). Jeśli podane, ma priorytet względem id.',
        example: 1,
    )]
    protected int $internalId;

    #[OA\Property(
        description: 'ID klienta, nadawane przez system ERP',
        example: '1',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Id klienta nadawane przez system ERP, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected string $clientId;

    #[OA\Property(
        description: 'Nazwa odbiorcy',
        example: 'Jan Kowalski',
    )]
    #[Assert\Length(
        max: 200,
        maxMessage: 'Nazwa odbiorcy, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected string $name;

    #[OA\Property(
        description: 'deliveryAddress'
    )]
    protected ?ReceiverDeliveryAddressDto $deliveryAddress;

    #[OA\Property(
        description: 'Adres e-mail',
        example: 'jan.kowalski@example.com',
    )]
    #[Assert\Length(
        max: 60,
        maxMessage: "Adres e-mail, może mieć maksymalnie {{ limit }} znaków",
    )]
    protected ?string $email;

    #[OA\Property(
        description: 'Numer telefonu',
        example: '+48 123 456 789',
    )]
    #[Assert\Length(
        max: 60,
        maxMessage: "Numer telefonu, może mieć maksymalnie {{ limit }} znaków",
    )]
    protected ?string $phone;

    #[OA\Property(
        description: 'Czy odbiorca jest domyślnym odbiorcą. Będzie domyślnie ustawiany przy składaniu zamówienia',
        example: true,
    )]
    protected bool $isDefault;

    #[OA\Property(
        description: 'Imię  osoby kontakowe',
        example: 'Jan',
    )]
    protected string $firstName;

    #[OA\Property(
        description: 'Nazwisko osoby kontakowej',
        example: 'Nowak',
    )]
    protected string $lastName;

    #[OA\Property(
        description: 'Czy odbiorca jest aktywny?',
        example: true,
    )]
    protected bool $isActive;

    #[OA\Property(
        description: 'Typ odbiorcy np. PRIMARY - piszemy z dużych liter',
        example: 'PRIMARY',
    )]
    protected ?string $type;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getInternalId(): int
    {
        return $this->internalId;
    }

    public function setInternalId(int $internalId): self
    {
        $this->internalId = $internalId;
        return $this;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;
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

    public function getDeliveryAddress(): ?ReceiverDeliveryAddressDto
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(?ReceiverDeliveryAddressDto $deliveryAddress): self
    {
        $this->deliveryAddress = $deliveryAddress;
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

    public function getIsDefault(): bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;
        return $this;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
