<?php

namespace Wise\GPSR\Service\ProductFullInfoDataProvider\Payload\Dto;

use ReflectionException;
use ReflectionProperty;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * Informacje o adresie
 */
class AddressInfo
{
    /**
     * Nazwa adresu
     * @var string|null
     */
    protected ?string $name;

    /**
     * Ulica
     * @var string|null
     */
    protected ?string $street;

    /**
     * Numer budynku
     * @var string|null
     */
    protected ?string $houseNumber;

    /**
     * Numer lokalu
     * @var string|null
     */
    protected ?string $apartmentNumber;

    /**
     * Miasto
     * @var string|null
     */
    protected ?string $city;

    /**
     * Kod pocztowy
     * @var string|null
     */
    protected ?string $postalCode;

    /**
     * Kod Kraju
     * @var string|null
     * @example 'pl
     */
    protected ?string $countryCode;

    /**
     * Kraj
     * @var string|null
     */
    protected ?string $country;

    /**
     * Stan
     * @var string|null
     */
    protected ?string $state;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(?string $houseNumber): self
    {
        $this->houseNumber = $houseNumber;

        return $this;
    }

    public function getApartmentNumber(): ?string
    {
        return $this->apartmentNumber;
    }

    public function setApartmentNumber(?string $apartmentNumber): self
    {
        $this->apartmentNumber = $apartmentNumber;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }
    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Zwraca obiekt w postaci tablicy
     * @return array
     * @throws ReflectionException
     */
    public function toArray(): array
    {
        return [
            'name' => $this->isInitialized('name') ? $this->getName() : null,
            'street' => $this->isInitialized('street') ? $this->getStreet() : null,
            'houseNumber' => $this->isInitialized('houseNumber') ? $this->getHouseNumber() : null,
            'apartmentNumber' => $this->isInitialized('apartmentNumber') ? $this->getApartmentNumber() : null,
            'city' => $this->isInitialized('city') ? $this->getCity() : null,
            'postalCode' => $this->isInitialized('postalCode') ? $this->getPostalCode() : null,
            'countryCode' => $this->isInitialized('countryCode') ? $this->getCountryCode() : null,
            'country' => $this->isInitialized('country') ? $this->getCountry() : null,
            'state' => $this->isInitialized('state') ? $this->getState() : null,
        ];
    }

    /**
     * Tworzy obiekt na podstawie tablicy
     * @param array $param
     * @return self
     */
    public static function fromArray(array $param): self
    {
        $address = new self();

        $address->setName($param['name'] ?? null);
        $address->setStreet($param['street'] ?? null);
        $address->setHouseNumber($param['houseNumber'] ?? null);
        $address->setApartmentNumber($param['apartmentNumber'] ?? null);
        $address->setCity($param['city'] ?? null);
        $address->setPostalCode($param['postalCode'] ?? null);
        $address->setCountryCode($param['countryCode'] ?? null);
        $address->setCountry($param['country'] ?? null);
        $address->setState($param['state'] ?? null);

        return $address;
    }

    /**
     * Sprawdzenie czy dany atrybut naszego obiektu DTO został zdefiniowany, przydatne po deserializacji requestu
     * @throws ReflectionException
     */
    #[Ignore]
    public function isInitialized(string $property): bool
    {
        $rp = new ReflectionProperty(static::class, $property);

        return $rp->isInitialized($this);
    }
}
