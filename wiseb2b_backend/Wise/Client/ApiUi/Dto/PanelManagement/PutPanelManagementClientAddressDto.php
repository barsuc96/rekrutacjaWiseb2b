<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto\PanelManagement;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\Api\Fields\FieldHandlingEnum;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class PutPanelManagementClientAddressDto extends CommonUiApiDto
{

    #[OA\Property(
        description: 'Identyfikator',
        example: 1,
    )]
    protected int $id;

    #[OA\Property(
        description: 'Nazwa adresu odbiorcy',
        example: 'Podstawowy',
    )]
    protected ?string $name = null;

    #[OA\Property(
        description: 'Ulica',
        example: 'Zdrojowa',
    )]
    protected ?string $street = null;

    #[OA\Property(
        description: 'Numer domu',
        example: '21a',
    )]
    protected ?string $houseNumber = null;

    #[OA\Property(
        description: 'Numer mieszkania',
        example: '2',
    )]
    protected ?string $apartmentNumber = null;

    #[OA\Property(
        description: 'Kod pocztowy odbiorcy',
        example: '63-456',
    )]
    protected ?string $postalCode = null;

    #[OA\Property(
        description: 'Miasto odbiorcy',
        example: 'Wrocław',
    )]
    protected ?string $city = null;

    #[OA\Property(
        description: 'Stan',
        example: 'example',
    )]
    protected ?string $state;

    #[OA\Property(
        description: 'Kod kraju odbiorcy',
        example: 'PL',
    )]
    protected ?string $countryCode = null;


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

    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }

    /**
     * @param string|null $houseNumber
     * @return self
     */
    public function setHouseNumber(?string $houseNumber): self
    {
        $this->houseNumber = $houseNumber ?? "";

        return $this;
    }

    /**
     * @return string
     */
    public function getApartmentNumber(): ?string
    {
        return $this->apartmentNumber;
    }

    /**
     * @param string|null $apartmentNumber
     * @return self
     */
    public function setApartmentNumber(?string $apartmentNumber): self
    {
        $this->apartmentNumber = $apartmentNumber ?? '';
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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }


}

