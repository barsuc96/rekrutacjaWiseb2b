<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Dto\Clients;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\ApiAdmin\Dto\AbstractSingleObjectAdminApiRequestDto;

class PutClientDto extends AbstractSingleObjectAdminApiRequestDto
{
    #[OA\Property(
        description: 'Id klienta identyfikujące klienta w ERP',
        example: 'CLIENT-123',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Id klienta, może mieć maksymalnie {{ limit }} znaków',
    )]
    #[FieldEntityMapping('idExternal')]
    protected string $id;

    #[OA\Property(
        description: 'ID wewnętrzne systemu B2B. Można używać zamiennie z id (o ile jest znane). Jeśli podane, ma priorytet względem id.',
        example: 1,
    )]
    #[FieldEntityMapping('id')]
    protected int $internalId;

    #[OA\Property(
        description: 'Nazwa klienta (dane rejestrowe)',
        example: 'Client name',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Nazwa klienta, może mieć maksymalnie {{ limit }} znaków",
    )]
    protected string $name;

    #[OA\Property(
        description: 'Adres rejestrowy'
    )]
    protected ?ClientRegisterAddressDto $registerAddress;

    protected ClientRepresentativeDto $clientRepresentative;

    #[OA\Property(
        description: 'Status klienta: "NEW" - do akceptacji, "ACTIVE" - zaakceptowany, "ARCHIVE" - archiwizowany',
        example: 3,
    )]
    #[FieldEntityMapping('status.symbol')]
    protected string $status;

    #[OA\Property(
        description: 'Główny adres e-mail',
        example: 'example@example.com',
    )]
    #[Assert\Length(
        max: 60,
        maxMessage: "Email odbiorcy, może mieć maksymalnie {{ limit }} znaków",
    )]
    protected string $email;

    #[OA\Property(
        description: 'Numer telefonu',
        example: '+48777777777',
    )]
    #[Assert\Length(
        max: 60,
        maxMessage: "Email odbiorcy, może mieć maksymalnie {{ limit }} znaków",
    )]
    protected ?string $phone;

    #[OA\Property(
        description: 'Czy klient aktywny?',
        example: true,
    )]
    protected bool $isActive;

    #[OA\Property(
        description: 'Identyfikator płatnika dla klienta w ERP',
        example: 'CLIENT-234',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Id płatnika, może mieć maksymalnie {{ limit }} znaków',
    )]
    #[FieldEntityMapping('clientParentIdExternal')]
    protected ?string $clientParentId;

    #[OA\Property(
        description: 'ID domyślnej metody płatności w ERP',
        example: "PAYMENT-123",
    )]
    #[FieldEntityMapping('defaultPaymentMethodIdExternal')]
    protected ?string $defaultPaymentMethodId;

    #[OA\Property(
        description: 'ID domyślnej metody dostawy w ERP',
        example: "DELIVERY-123",
    )]
    #[FieldEntityMapping('defaultDeliveryMethodIdExternal')]
    protected ?string $defaultDeliveryMethodId;

    #[OA\Property(
        description: 'Flagi dla klienta',
        example: 'FLAG',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Flagi, mogą mieć maksymalnie {{ limit }} znaków',
    )]
    protected ?string $flags;

    #[OA\Property(
        description: 'Identyfikator podatkowy klienta, z identyfikatorem karju w UE',
        example: 'PL1234567890',
    )]
    #[Assert\Length(
        max: 20,
        maxMessage: 'Identyfikator podatkowy, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected string $taxNumber;

    #[OA\Property(
        description: 'Numer rachunku bankowego do zwrotów'
    )]
    protected ?ClientReturnBankAccountDto $returnBankAccount;

    #[OA\Property(
        description: 'Limit kupiecki całkowity przyznany',
        example: 5.26,
    )]
    protected ?float $tradeCreditTotal;

    #[OA\Property(
        description: 'Limit kupiecki do wykorzystania',
        example: 3.29,
    )]
    protected ?float $tradeCreditFree;

    #[OA\Property(
        description: 'Domyslna waluta rozliczeniowa danego klienta',
        example: 'PLN',
    )]
    #[Assert\Length(
        max: 3,
        maxMessage: 'Waluta, może mieć maksymalnie {{ limit }} znaki',
    )]
    protected ?string $defaultCurrency;

    #[OA\Property(
        description: 'Rodzaj klienta: CANDIDATE;VERIFIED',
        example: 'CANDIDATE',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Typ klienta, może mieć maksymalnie {{ limit }} znaków',
    )]
    protected ?string $type;

    #[OA\Property(
        description: 'Koszt związany z Dropshipping. Stały %, np. 5',
        example: 5.55,
    )]
    protected ?float $dropshippingCost;

    #[OA\Property(
        description: 'Koszt związany ze zwrotami. Stały % opłaty od ceny zakupu',
        example: 9.85,
    )]
    protected float $orderReturnCost;

    #[OA\Property(
        description: 'Próg darmowej dostawy',
        example: 8.52,
    )]
    protected float $freeDeliveryLimit;

    #[OA\Property(
        description: 'Rabat',
        example: 8.99,
    )]
    protected ?float $discount;

    #[OA\Property(
        description: 'Identyfikator handlowca w ERP',
        example: 'TRADER-123',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Id zewnętrzne handlowca, może mieć maksymalnie {{ limit }} znaków",
    )]
    #[FieldEntityMapping('traderIdExternal')]
    protected ?string $traderId;

    #[OA\Property(
        description: 'Identyfikator handlowca w wiseB2B',
        example: 0,
    )]
    #[FieldEntityMapping('traderId')]
    protected ?int $traderInternalId;

    #[OA\Property(
        description: 'Identyfikator cennika w ERP',
        example: 'PRICELIST-123',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Id zewnętrzne cennika, może mieć maksymalnie {{ limit }} znaków",
    )]
    #[FieldEntityMapping('pricelistIdExternal')]
    protected ?string $pricelistId;

    #[Assert\Length(
        max: 255,
        maxMessage: "Id zewnętrzne grupy klientów, może mieć maksymalnie {{ limit }} znaków",
    )]
    #[FieldEntityMapping('clientGroupIdExternal')]
    protected ?string $clientGroupId;

    #[OA\Property(
        description: 'Identyfikator grupy klientów w wiseB2B',
        example: 1,
    )]
    #[FieldEntityMapping('clientGroupId')]
    protected ?int $clientGroupInternalId;

    #[OA\Property(
        description: 'Identyfikator cennika w wiseB2B',
        example: 1,
    )]
    #[FieldEntityMapping('pricelistId')]
    protected ?int $pricelistInternalId;

    #[OA\Property(
        description: 'Imię kontrahenta',
        example: 'Adam',
    )]
    protected ?string $firstName;

    #[OA\Property(
        description: 'Nazwisko kontrahenta',
        example: 'Kowalski',
    )]
    protected ?string $lastName;

    #[OA\Property(
        description: 'Czy został zweryfikowany w systemie VIES',
        example: true,
    )]
    protected ?bool $isVies;

    /** @var ClientPaymentMethodAggregateDto[] */
    protected ?array $payments;

    /** @var ClientDeliveryMethodAggregateDto[] */
    protected ?array $deliveries;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRegisterAddress(): ?ClientRegisterAddressDto
    {
        return $this->registerAddress;
    }

    public function setRegisterAddress(?ClientRegisterAddressDto $registerAddress): self
    {
        $this->registerAddress = $registerAddress;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

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

    public function getClientParentId(): ?string
    {
        return $this->clientParentId;
    }

    public function setClientParentId(?string $clientParentId): self
    {
        $this->clientParentId = $clientParentId;

        return $this;
    }

    public function getDefaultPaymentMethodId(): ?string
    {
        return $this->defaultPaymentMethodId;
    }

    public function setDefaultPaymentMethodId(?string $defaultPaymentMethodId): self
    {
        $this->defaultPaymentMethodId = $defaultPaymentMethodId;

        return $this;
    }

    public function getDefaultDeliveryMethodId(): ?string
    {
        return $this->defaultDeliveryMethodId;
    }

    public function setDefaultDeliveryMethodId(?string $defaultDeliveryMethodId): self
    {
        $this->defaultDeliveryMethodId = $defaultDeliveryMethodId;

        return $this;
    }

    public function getFlags(): ?string
    {
        return $this->flags;
    }

    public function setFlags(?string $flags): self
    {
        $this->flags = $flags;

        return $this;
    }

    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(string $taxNumber): self
    {
        $this->taxNumber = $taxNumber;

        return $this;
    }

    public function getReturnBankAccount(): ?ClientReturnBankAccountDto
    {
        return $this->returnBankAccount;
    }

    public function setReturnBankAccount(?ClientReturnBankAccountDto $returnBankAccount): self
    {
        $this->returnBankAccount = $returnBankAccount;

        return $this;
    }

    public function getTradeCreditTotal(): ?float
    {
        return $this->tradeCreditTotal;
    }

    public function setTradeCreditTotal(?float $tradeCreditTotal): self
    {
        $this->tradeCreditTotal = $tradeCreditTotal;

        return $this;
    }

    public function getTradeCreditFree(): ?float
    {
        return $this->tradeCreditFree;
    }

    public function setTradeCreditFree(?float $tradeCreditFree): self
    {
        $this->tradeCreditFree = $tradeCreditFree;

        return $this;
    }

    public function getDefaultCurrency(): ?string
    {
        return $this->defaultCurrency;
    }

    public function setDefaultCurrency(?string $defaultCurrency): self
    {
        $this->defaultCurrency = $defaultCurrency;

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

    public function getDropshippingCost(): ?float
    {
        return $this->dropshippingCost;
    }

    public function setDropshippingCost(?float $dropshippingCost): self
    {
        $this->dropshippingCost = $dropshippingCost;

        return $this;
    }

    public function getOrderReturnCost(): float
    {
        return $this->orderReturnCost;
    }

    public function setOrderReturnCost(float $orderReturnCost): self
    {
        $this->orderReturnCost = $orderReturnCost;

        return $this;
    }

    public function getFreeDeliveryLimit(): float
    {
        return $this->freeDeliveryLimit;
    }

    public function setFreeDeliveryLimit(float $freeDeliveryLimit): self
    {
        $this->freeDeliveryLimit = $freeDeliveryLimit;

        return $this;
    }

    public function getPayments(): ?array
    {
        return $this->payments;
    }

    public function setPayments(?array $payments): self
    {
        $this->payments = $payments;

        return $this;
    }

    public function getDeliveries(): ?array
    {
        return $this->deliveries;
    }

    public function setDeliveries(?array $deliveries): self
    {
        $this->deliveries = $deliveries;

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(?float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getTraderId(): ?string
    {
        return $this->traderId;
    }

    public function setTraderId(?string $traderId): self
    {
        $this->traderId = $traderId;

        return $this;
    }

    public function getTraderInternalId(): ?int
    {
        return $this->traderInternalId;
    }

    public function setTraderInternalId(?int $traderInternalId): self
    {
        $this->traderInternalId = $traderInternalId;

        return $this;
    }

    public function getPricelistId(): ?string
    {
        return $this->pricelistId;
    }

    public function setPricelistId(?string $pricelistId): self
    {
        $this->pricelistId = $pricelistId;

        return $this;
    }

    public function getClientGroupId(): ?string
    {
        return $this->clientGroupId;
    }

    public function setClientGroupId(?string $clientGroupId): self
    {
        $this->clientGroupId = $clientGroupId;

        return $this;
    }

    public function getClientGroupInternalId(): ?int
    {
        return $this->clientGroupInternalId;
    }

    public function setClientGroupInternalId(?int $clientGroupInternalId): self
    {
        $this->clientGroupInternalId = $clientGroupInternalId;

        return $this;
    }

    public function getPricelistInternalId(): ?int
    {
        return $this->pricelistInternalId;
    }

    public function setPricelistInternalId(?int $pricelistInternalId): self
    {
        $this->pricelistInternalId = $pricelistInternalId;

        return $this;
    }

    public function getClientRepresentative(): ClientRepresentativeDto
    {
        return $this->clientRepresentative;
    }

    public function setClientRepresentative(ClientRepresentativeDto $clientRepresentative): self
    {
        $this->clientRepresentative = $clientRepresentative;

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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIsVies(): ?bool
    {
        return $this->isVies;
    }

    public function setIsVies(?bool $isVies): self
    {
        $this->isVies = $isVies;

        return $this;
    }
}
