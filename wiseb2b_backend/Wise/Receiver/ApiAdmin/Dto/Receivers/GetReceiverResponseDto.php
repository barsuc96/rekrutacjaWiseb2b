<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiAdmin\Dto\Receivers;

use OpenApi\Attributes as OA;
use Wise\Core\ApiAdmin\Dto\DateInsertUpdateDtoTrait;
use Wise\Core\Dto\AbstractResponseDto;
use Wise\Core\Model\Address;

class GetReceiverResponseDto extends AbstractResponseDto
{
    use DateInsertUpdateDtoTrait;

    #[OA\Property(
        description: 'ID nadawane przez system ERP',
        example: '1',
    )]
    protected string $id;

    #[OA\Property(
        description: 'ID wewnętrzne systemu B2B. Można używać zamiennie z id (o ile jest znane). Jeśli podane, ma priorytet względem id.',
        example: 1,
    )]
    protected int $internalId;

    #[OA\Property(
        description: 'ID nadawane przez system ERP',
        example: '1',
    )]
    protected string $clientId;

    #[OA\Property(
        description: 'Nazwa odbiorcy',
        example: 'Jan Kowalski',
    )]
    protected string $name;

    #[OA\Property(
        description: 'Adres odbiorcy',
    )]
    protected ?Address $deliveryAddress;

    #[OA\Property(
        description: 'Adres e-mail',
        example: 'jan.kowalski@example.com',
    )]
    protected ?string $email;

    #[OA\Property(
        description: 'Numer telefonu',
        example: '+48 123 456 789',
    )]
    protected ?string $phone;

    #[OA\Property(
        description: 'Czy odbiorca jest domyślnym odbiorcą. Będzie domyślnie ustawiany przy składaniu zamówienia',
        example: true,
    )]
    protected bool $isDefault;

    #[OA\Property(
        description: 'Imię osoby kontakowej',
        example: 'Jan',
    )]
    protected ?string $firstName;

    #[OA\Property(
        description: 'Nazwisko osoby kontakowej',
        example: 'Nowak',
    )]
    protected ?string $lastName;

    #[OA\Property(
        description: 'Czy odbiorca jest aktywny?',
        example: true,
    )]
    protected bool $isActive;

    #[OA\Property(
        description: 'Typ odbiorcy np. primary',
        example: 'primary',
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

    public function getDeliveryAddress(): ?Address
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(?Address $deliveryAddress): self
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

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;
        return $this;
    }

    public function getContactName(): string
    {
        return $this->contactName;
    }

    public function setContactName(string $contactName): self
    {
        $this->contactName = $contactName;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
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
