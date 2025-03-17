<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use Wise\Core\Dto\AbstractResponseDto;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Validator\Constraints as WiseAssert;

class AddressDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Ulica',
        example: 'Zdrojowa',
    )]
    #[WiseAssert\NotBlank]
    protected ?string $street = null;

    #[OA\Property(
        description: 'Numer domu',
        example: '21a',
    )]
    #[WiseAssert\NotBlank]
    protected ?string $building = null;

    #[OA\Property(
        description: 'Numer mieszkania',
        example: '2',
    )]
    protected ?string $apartment = null;

    #[OA\Property(
        description: 'Kod pocztowy odbiorcy',
        example: '63-456',
    )]
    #[WiseAssert\NotBlank]
    protected ?string $postalCode = null;

    #[OA\Property(
        description: 'Miasto odbiorcy',
        example: 'Wrocław',
    )]
    #[WiseAssert\NotBlank]
    protected ?string $city = null;

    #[OA\Property(
        description: 'Stan',
        example: 'example',
    )]
    protected ?string $state;

    #[OA\Property(
        description: 'Kraj odbiorcy',
        example: 'Polska',
    )]
    protected ?string $country = null;

    #[OA\Property(
        description: 'Kod kraju odbiorcy',
        example: 'PL',
    )]
    #[WiseAssert\NotBlank]
    protected ?string $countryCode = null;

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street ?? '';
        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode ?? '';
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city ?? '';
        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country ?? '';

        return $this;
    }

    public function getBuilding(): ?string
    {
        return $this->building;
    }

    /**
     * @param string|null $building
     * @return self
     */
    public function setBuilding(?string $building): self
    {
        $this->building = $building ?? "";
        return $this;
    }

    /**
     * @return string
     */
    public function getApartment(): ?string
    {
        return $this->apartment;
    }

    /**
     * @param string|null $apartment
     * @return self
     */
    public function setApartment(?string $apartment): self
    {
        $this->apartment = $apartment ?? '';
        return $this;
    }

    /**
     * @return
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * @param  $countryCode
     * @return self
     */
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
