<?php

declare(strict_types=1);


namespace Wise\Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait z danymi adresowymi, do użycia w różnych encjach
 */
trait AddressDataTrait
{
    #[ORM\Column(length: 60)]
    protected ?string $name = null;

    #[ORM\Column(length: 60)]
    protected ?string $street = null;

    #[ORM\Column(length: 10)]
    protected ?string $houseNumber = null;

    #[ORM\Column(length: 10, nullable: true)]
    protected ?string $apartmentNumber = null;

    #[ORM\Column(length: 60)]
    protected ?string $city = null;

    #[ORM\Column(length: 10)]
    protected ?string $postalCode = null;

    #[ORM\Column(length: 3)]
    protected ?string $countryCode = null;

    #[ORM\Column(length: 32)]
    protected ?string $state = null;

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
}
