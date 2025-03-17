<?php

namespace Wise\GPSR\Domain\GpsrSupplier;

use Wise\Core\Domain\Event\DomainEventManager;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Model\Address;
use Wise\GPSR\Domain\GpsrSupplier\Event\GpsrSupplierHasChangedEvent;

/**
 * Encja reprezentująca dostawcę, wykorzystywane m.in w GPSR
 */
class GpsrSupplier extends AbstractEntity
{
    /**
     * Id zewnętrzne (z systemu klienta)
     * @var string|null
     */
    protected ?string $idExternal;

    /**
     * Unikalny symbol dostawcy, może być ten sam jak idExternal,
     * ale żebyśmy mogli go w kodzie powiązać
     * @var string|null
     */
    protected ?string $symbol;

    /**
     * Numer NIP
     * @var string|null
     */
    protected ?string $taxNumber;

    /**
     * Adres dostawcy
     * @var Address|null
     */
    protected ?Address $address = null;

    /**
     * Zarejestrowana nazwa handlowa
     * @var string|null
     */
    protected ?string $registeredTradeName = null;

    /**
     * Adres e-mail
     * @var string|null
     */
    protected ?string $email;

    /**
     * Numer telefonu
     * @var string|null
     */
    protected ?string $phone;

    public function getIdExternal(): ?string
    {
        return $this->idExternal;
    }

    public function setIdExternal(?string $idExternal): self
    {
        $this->idExternal = $idExternal;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(?string $symbol): self
    {
        $this->symbol = $symbol;

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

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

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

    protected function entityHasChanged(string $newHash): void
    {
        parent::entityHasChanged($newHash);

        DomainEventManager::instance()->post(new GpsrSupplierHasChangedEvent($this->getId()));
    }

    public function getRegisteredTradeName(): ?string
    {
        return $this->registeredTradeName;
    }

    public function setRegisteredTradeName(?string $registeredTradeName): self
    {
        $this->registeredTradeName = $registeredTradeName;

        return $this;
    }
}
