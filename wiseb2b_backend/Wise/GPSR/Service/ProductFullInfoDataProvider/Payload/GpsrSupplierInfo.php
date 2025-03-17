<?php

namespace Wise\GPSR\Service\ProductFullInfoDataProvider\Payload;

use Wise\Core\Entity\PayloadBag\AbstractPayload;
use Wise\GPSR\Service\ProductFullInfoDataProvider\Payload\Dto\AddressInfo;

/**
 *  Informacje o GPSR
 */
class GpsrSupplierInfo extends AbstractPayload
{
    /**
     * Identyfikator
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * Symbol dostawy
     * @var string|null
     */
    protected ?string $symbol = null;

    /**
     * Numer NIP
     * @var string|null
     */
    protected ?string $taxNumber = null;

    /**
     * Numer telefonu
     * @var string|null
     */
    protected ?string $phone = null;

    /**
     * Adres e-mail
     * @var string|null
     */
    protected ?string $email = null;

    /**
     * Zarejestrowana nazwa handlowa
     * @var string|null
     */
    protected ?string $registeredTradeName = null;

    /**
     * Adres dostawcy
     */
    protected ?AddressInfo $address;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

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

    public function getAddress(): ?AddressInfo
    {
        return $this->address;
    }

    public function setAddress(?AddressInfo $address): self
    {
        $this->address = $address;

        return $this;
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

    /**
     * Tworzy obiekt na podstawie tablicy
     * @param array|null $data
     * @return self|null
     */
    public static function fromArray(?array $data): ?self
    {
        if(empty($data)){
            return null;
        }

        $supplierDto = new self();

        $supplierDto->setId($data['id'] ?? null)
            ->setSymbol($data['symbol'] ?? null)
            ->setTaxNumber($data['taxNumber'] ?? null)
            ->setPhone($data['phone'] ?? null)
            ->setEmail($data['email'] ?? null)
            ->setRegisteredTradeName($data['registeredTradeName'] ?? null);

        if(!empty($data['address'])){
            $address = AddressInfo::fromArray($data['address']);
            $supplierDto->setAddress($address);
        }

        return $supplierDto;
    }
}
