<?php

declare(strict_types=1);

namespace Wise\Client\Domain\Client;

use DateTimeInterface;
use Wise\Client\Domain\Client\Events\ClientHasChangedEvent;
use Wise\Client\Domain\Client\Events\ClientTaxNumberHasChangedEvent;
use Wise\Client\Domain\Client\Events\ClientViesChangedEvent;
use Wise\Client\Domain\ClientRepresentative\ClientRepresentative;
use Wise\Core\Domain\Event\DomainEventManager;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Helper\Object\AddressHelper;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Validator\Constraints as WiseAssert;
use Wise\Core\Helper\Object\BankAccountHelper;
use Wise\Core\Model\Address;
use Wise\Core\Model\BankAccount;
use Wise\Core\Validator\Enum\ConstraintTypeEnum;


class Client extends AbstractEntity
{
    protected ?string $idExternal = null;

    protected ?string $name = null;

    protected ?int $clientParentId = null;

    protected ?int $status = null;

    #[Assert\Email()]
    #[WiseAssert\NotBlank(
        payload: ["constraintType" => ConstraintTypeEnum::ERROR],
    )]
    protected ?string $email = null;

    protected ?string $firstName = null;

    protected ?string $lastName = null;


    protected ?string $phone = null;

    protected ?string $website = null;

    protected ?string $taxNumber = null;

    protected ?int $defaultPaymentMethodId;

    protected ?int $defaultDeliveryMethodId;

    protected ?string $flags;

    protected ?float $tradeCreditTotal;

    protected ?float $tradeCreditFree;

    protected ?string $defaultCurrency;

    protected ?string $type;

    protected ?float $dropshippingCost;

    protected ?float $orderReturnCost;

    protected ?float $freeDeliveryLimit;

    protected ?float $discount = null;

    protected ?int $traderId = null;

    protected ?int $pricelistId = null;

    #[Assert\NotBlank]
    protected ?int $clientGroupId = null;

    protected ?Address $registerAddress = null;
    protected ?BankAccount $returnBankAccount = null;

    protected ?ClientRepresentative $clientRepresentative = null;

    protected ?bool $isVies = null;
    protected ?DateTimeInterface $viesLastUpdate = null;

    public function getRegisterAddress(): ?Address
    {
        return $this->registerAddress;
    }

    public function setRegisterAddress(?Address $registerAddress): self
    {
        $this->registerAddress = $registerAddress;

        return $this;
    }

    public function getReturnBankAccount(): ?BankAccount
    {
        return $this->returnBankAccount;
    }

    public function setReturnBankAccount(null|BankAccount|array $returnBankAccount): self
    {
        $this->returnBankAccount = BankAccountHelper::convert($returnBankAccount);

        return $this;
    }

    public function getIdExternal(): ?string
    {
        return $this->idExternal;
    }

    public function setIdExternal(?string $idExternal): self
    {
        $this->idExternal = $idExternal;

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

    public function getClientParentId(): ?int
    {
        return $this->clientParentId;
    }

    public function setClientParentId(?int $clientParentId): self
    {
        $this->clientParentId = $clientParentId;

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

    public function getTaxNumber(): ?string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(?string $taxNumber): self
    {
        if($this->taxNumber !== $taxNumber){
            $this->taxNumber = $taxNumber;

            DomainEventManager::instance()->post(new ClientTaxNumberHasChangedEvent($this));
        }

        return $this;
    }

    public function getDefaultPaymentMethodId(): ?int
    {
        return $this->defaultPaymentMethodId;
    }

    public function setDefaultPaymentMethodId(?int $defaultPaymentMethodId): self
    {
        $this->defaultPaymentMethodId = $defaultPaymentMethodId;

        return $this;
    }

    public function getDefaultDeliveryMethodId(): ?int
    {
        return $this->defaultDeliveryMethodId;
    }

    public function setDefaultDeliveryMethodId(?int $defaultDeliveryMethodId): self
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

    public function getTraderId(): ?int
    {
        return $this->traderId;
    }

    public function setTraderId(?int $traderId): self
    {
        $this->traderId = $traderId;

        return $this;
    }

    public function getPricelistId(): ?int
    {
        return $this->pricelistId;
    }

    public function setPricelistId(?int $pricelistId): self
    {
        $this->pricelistId = $pricelistId;

        return $this;
    }

    public function getClientGroupId(): ?int
    {
        return $this->clientGroupId;
    }

    public function setClientGroupId(?int $clientGroupId): self
    {
        $this->clientGroupId = $clientGroupId;

        return $this;
    }

    public function getClientRepresentative(): ?ClientRepresentative
    {
        return $this->clientRepresentative;
    }

    public function setClientRepresentative(?ClientRepresentative $clientRepresentative): self
    {
        $this->clientRepresentative = $clientRepresentative;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    protected function entityHasChanged(string $newHash): void
    {
        parent::entityHasChanged($newHash);

        DomainEventManager::instance()->post(new ClientHasChangedEvent($this->getId()));
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
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
        if($this->isVies !== $isVies){
            $this->isVies = $isVies;

            DomainEventManager::instance()->post(new ClientViesChangedEvent($this->getId()));
        }

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


}
