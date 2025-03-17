<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Dto\Clients;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\ApiAdmin\Dto\CommonAdminApiDto;
use Wise\Core\ApiAdmin\Dto\CommonDateInsertUpdateDtoTrait;
use Wise\Core\Model\Address;
use Wise\Core\Model\BankAccount;

class GetClientDto extends CommonAdminApiDto
{
    use CommonDateInsertUpdateDtoTrait;

    // ====== PARAMETERS DTO ======

    #[OA\Query(
        description: 'Czy pobierać też metody płatności?',
        example: false,
        aggregates: 'payments',
        onlyAggregates: true
    )]
    protected bool $fetchPayments;

    #[OA\Query(
        description: 'Czy pobierać też metody dostawy?',
        example: false,
        aggregates: 'deliveries',
        onlyAggregates: true
    )]
    protected bool $fetchDeliveries;


    // ====== RESPONSE DTO ======

    #[OA\Property(
        description: 'Id klienta identyfikujące klienta w ERP',
        example: 'CLIENT-123',
    )]
    #[FieldEntityMapping('idExternal')]
    protected ?string $id;

    #[OA\Property(
        description: 'ID wewnętrzne systemu B2B. Można używać zamiennie z id (o ile jest znane). Jeśli podane, ma priorytet względem id.',
        example: 1,
    )]
    #[FieldEntityMapping('id')]
    protected ?int $internalId;

    #[OA\Property(
        description: 'Nazwa klienta (dane rejestrowe)',
        example: 'Jan Nowak',
    )]
    protected ?string $name;

    #[OA\Property(
        description: 'Adres rejestrowy',
    )]
    protected ?Address $registerAddress;

    #[OA\Property(
        description: 'Główny adres e-mail',
        example: 'jan.nowak@example.com',
    )]
    protected ?string $email;

    #[OA\Property(
        description: 'Numer telefonu',
        example: '+48 123 456 789',
    )]
    protected ?string $phone;

    #[OA\Property(
        description: 'Czy klient aktywny?',
        example: true,
    )]
    protected ?bool $isActive;

    #[OA\Property(
        description: 'Identyfikator płatnika dla klienta w ERP',
        example: 1,
    )]
    #[FieldEntityMapping('clientParentId.idExternal')]
    protected ?int $clientParentId;

    #[OA\Property(
        description: 'Identyfikator płatnika dla klienta systemu B2B',
        example: 1,
    )]
    #[FieldEntityMapping('clientParentId.id')]
    protected ?int $clientParentInternalId;

    #[OA\Property(
        description: 'Status klienta',
        example: 'NEW',
    )]
    #[FieldEntityMapping('status.symbol')]
    protected ?string $status;

    #[OA\Property(
        description: 'ID domyślnej metody płatności w ERP',
        example: 'PAYMENT-123',
    )]
    #[FieldEntityMapping('defaultPaymentMethodId.idExternal')]
    protected ?string $defaultPaymentMethodId;

    #[OA\Property(
        description: 'ID domyślnej metody dostawy w ERP',
        example: 'DELIVERY-123',
    )]
    #[FieldEntityMapping('defaultDeliveryMethodId.idExternal')]
    protected ?string $defaultDeliveryMethodId;

    #[OA\Property(
        description: 'Flagi dla klienta',
        example: 'example',
    )]
    protected ?string $flags;

    #[OA\Property(
        description: 'Identyfikator podatkowy klienta, z identyfikatorem karju w UE',
        example: 'PL8951718379',
    )]
    protected ?string $taxNumber;

    #[OA\Property(
        description: 'Numer rachunku bankowego do zwrotów',
    )]
    protected ?BankAccount $returnBankAccount;

    #[OA\Property(
        description: 'Limit kupiecki całkowity przyznany',
        example: 40,
    )]
    protected ?float $tradeCreditTotal;

    #[OA\Property(
        description: 'Limit kupiecki do wykorzystania',
        example: 10,
    )]
    protected ?float $tradeCreditFree;

    #[OA\Property(
        description: 'Domyslna waluta rozliczeniowa danego klienta',
        example: 'PLN',
    )]
    protected ?string $defaultCurrency;

    #[OA\Property(
        description: 'Rodzaj klienta: [CANDIDATE, VERIFIED]',
        example: 'CANDIDATE',
    )]
    protected ?string $type;

    #[OA\Property(
        description: 'Koszt związany z Dropshipping. Stały %, np. 5',
        example: 5,
    )]
    protected ?float $dropshippingCost;

    #[OA\Property(
        description: 'Koszt związany ze zwrotami. Stały % opłaty od ceny zakupu',
        example: 6,
    )]
    protected ?float $orderReturnCost;

    #[OA\Property(
        description: 'Próg darmowej dostawy',
        example: 10,
    )]
    protected ?float $freeDeliveryLimit;

    #[OA\Property(
        description: 'Rabat',
        example: 8.99,
    )]
    protected ?float $discount;

    #[OA\Property(
        description: 'Identyfikator handlowca w ERP',
        example: 'TRADER-123',
    )]
    #[FieldEntityMapping('traderId.idExternal')]
    protected ?string $traderId;

    #[OA\Property(
        description: 'Identyfikator handlowca w wiseB2B',
        example: 1,
    )]
    #[FieldEntityMapping('traderId.id')]
    protected ?int $traderInternalId;

    #[OA\Property(
        description: 'Identyfikator cennika w ERP',
        example: 'PRICELIST-123',
    )]
    #[FieldEntityMapping('pricelistId.idExternal')]
    protected ?string $pricelistId;

    #[OA\Property(
        description: 'Identyfikator cennika w wiseB2B',
        example: 1,
    )]
    #[FieldEntityMapping('pricelistId.id')]
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
        description: 'Imię klienta - kontaktowe',
        example: 'Jan',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Imię, może mieć maksymalnie {{ limit }} znaków',
    )]
    #[FieldEntityMapping('clientRepresentative.personFirstname')]
    protected ?string $personFirstname;

    #[OA\Property(
        description: 'Nazwisko klienta - kontaktowe',
        example: 'Kowalski',
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Nazwisko, może mieć maksymalnie {{ limit }} znaków',
    )]
    #[FieldEntityMapping('clientRepresentative.personLastname')]
    protected ?string $personLastname;

    #[OA\Property(
        description: 'Czy został zweryfikowany w systemie VIES',
        example: true,
    )]
    protected ?bool $isVies = null;

    #[OA\Property(
        description: 'Kiedy ostatnio sprawdzono status w VIES',
        example: '2024-08-16',
    )]
    protected ?DateTimeInterface $viesLastUpdate = null;

    #[OA\Property(
        description: 'Identyfikator sklepu',
        example: 1,
    )]
    #[FieldEntityMapping('clientGroupId.storeId')]
    protected ?int $storeId;

    /** @var ClientPaymentMethodAggregateDto[] */
    protected ?array $payments;

    /** @var ClientDeliveryMethodAggregateDto[] */
    protected ?array $deliveries;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getInternalId(): ?int
    {
        return $this->internalId;
    }

    public function setInternalId(?int $internalId): self
    {
        $this->internalId = $internalId;

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

    public function getRegisterAddress(): ?Address
    {
        return $this->registerAddress;
    }

    public function setRegisterAddress(?Address $registerAddress): self
    {
        $this->registerAddress = $registerAddress;

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

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getClientParentId(): ?int
    {
        return $this->clientParentId;
    }

    public function setClientParentId(?int $clientParentId): self
    {
        $this->clientParentId = $clientParentId;

        return $this;
    }

    public function getClientParentInternalId(): ?int
    {
        return $this->clientParentInternalId;
    }

    public function setClientParentInternalId(?int $clientParentInternalId): self
    {
        $this->clientParentInternalId = $clientParentInternalId;

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

    public function getTaxNumber(): ?string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(?string $taxNumber): self
    {
        $this->taxNumber = $taxNumber;

        return $this;
    }

    public function getReturnBankAccount(): ?BankAccount
    {
        return $this->returnBankAccount;
    }

    public function setReturnBankAccount(?BankAccount $returnBankAccount): self
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

    public function getOrderReturnCost(): ?float
    {
        return $this->orderReturnCost;
    }

    public function setOrderReturnCost(?float $orderReturnCost): self
    {
        $this->orderReturnCost = $orderReturnCost;

        return $this;
    }

    public function getFreeDeliveryLimit(): ?float
    {
        return $this->freeDeliveryLimit;
    }

    public function setFreeDeliveryLimit(?float $freeDeliveryLimit): self
    {
        $this->freeDeliveryLimit = $freeDeliveryLimit;

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

    public function getPricelistInternalId(): ?int
    {
        return $this->pricelistInternalId;
    }

    public function setPricelistInternalId(?int $pricelistInternalId): self
    {
        $this->pricelistInternalId = $pricelistInternalId;

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

    public function getPersonFirstname(): ?string
    {
        return $this->personFirstname;
    }

    public function setPersonFirstname(?string $personFirstname): self
    {
        $this->personFirstname = $personFirstname;

        return $this;
    }

    public function getPersonLastname(): ?string
    {
        return $this->personLastname;
    }

    public function setPersonLastname(?string $personLastname): self
    {
        $this->personLastname = $personLastname;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
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

    public function getViesLastUpdate(): ?DateTimeInterface
    {
        return $this->viesLastUpdate;
    }

    public function setViesLastUpdate(?DateTimeInterface $viesLastUpdate): self
    {
        $this->viesLastUpdate = $viesLastUpdate;

        return $this;
    }

    public function getStoreId(): ?int
    {
        return $this->storeId;
    }

    public function setStoreId(?int $storeId): self
    {
        $this->storeId = $storeId;

        return $this;
    }
}
