<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\Api\Dto\CommonParameterListTrait;
use Wise\Core\Api\Fields\FieldHandlingEnum;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class ClientResponseDto extends CommonUiApiDto
{
    use CommonParameterListTrait;

    #[OA\Query(
        description: 'Filtrowanie na podstawie statusu',
        example: 1,
    )]
    protected ?int $statusFilter;

    #[OA\Property(
        description: 'Id wewnętrzne',
        example: 1,
    )]
    protected ?int $id;

    #[OA\Property(
        description: 'Identyfikator zewnętrzny klienta',
        example: '54343',
    )]
    protected ?string $idExternal;

    #[OA\Property(
        description: 'Nazwa klienta',
        example: 'Quattro Forum',
    )]
    protected ?string $name;

    #[OA\Property(
        description: 'Imię klienta',
        example: 'Adam',
    )]
    protected ?string $firstName;

    #[OA\Property(
        description: 'Nazwisko klienta',
        example: 'Kowalski',
    )]
    protected ?string $lastName;

    #[OA\Property(
        description: 'Adres e-mail klienta',
        example: 'dkowalczyk@sente.pl',
    )]
    protected ?string $email;

    #[OA\Property(
        description: 'Status klienta',
        example: 1,
    )]
    #[FieldEntityMapping('status.id')]
    protected ?int $status;

    #[OA\Property(
        description: 'Numer telefonu klienta',
        example: '123456789',
    )]
    protected ?string $phone;

    #[OA\Property(
        description: 'Liczba ofert',
        type: 'integer',
        example: 5,
    )]
    protected int $offersCount;

    #[OA\Property(
        description: 'Liczba zamówień',
        type: 'integer',
        example: 3,
    )]
    protected int $ordersCount;

    #[OA\Property(
        description: 'Adres klienta',
    )]
    #[FieldEntityMapping('registerAddress')]
    protected ?AddressDto $address;

    #[OA\Property(
        description: 'Status klienta',
        example: 'NEW',
    )]
    #[FieldEntityMapping('status.symbol')]
    protected ?string $statusSymbol;

    #[OA\Property(
        description: 'Status klienta (Do wyświetlenia)',
        example: 'Do weryfikacji',
    )]
    #[FieldEntityMapping(null)]
    protected ?string $statusFormatted;

    protected ?StoreDto $store;


    #[OA\Property(
        description: 'Identyfikator sklepu',
        example: 1,
    )]
    #[FieldEntityMapping('clientGroupId.storeId')]
    protected ?int $storeId;

    #[OA\Property(
        description: 'Symbol sklepu',
        example: 1,
    )]
    #[FieldEntityMapping(FieldHandlingEnum::HANDLE_BY_TRANSFER_AND_RETURN_IN_RESPONSE)]
    protected ?string $storeSymbol;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return ClientResponseDto
     */
    public function setId(?int $id): ClientResponseDto
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return ClientResponseDto
     */
    public function setName(?string $name): ClientResponseDto
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     * @return ClientResponseDto
     */
    public function setFirstName(?string $firstName): ClientResponseDto
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     * @return ClientResponseDto
     */
    public function setLastName(?string $lastName): ClientResponseDto
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return ClientResponseDto
     */
    public function setEmail(?string $email): ClientResponseDto
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return ClientResponseDto
     */
    public function setPhone(?string $phone): ClientResponseDto
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return AddressDto
     */
    public function getAddress(): ?AddressDto
    {
        return $this->address;
    }

    /**
     * @param AddressDto $address
     * @return ClientResponseDto
     */
    public function setAddress(?AddressDto $address): ClientResponseDto
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return int
     */
    public function getOffersCount(): int
    {
        return $this->offersCount;
    }

    /**
     * @param int $offersCount
     * @return ClientResponseDto
     */
    public function setOffersCount(int $offersCount): ClientResponseDto
    {
        $this->offersCount = $offersCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrdersCount(): int
    {
        return $this->ordersCount;
    }

    /**
     * @param int $ordersCount
     * @return ClientResponseDto
     */
    public function setOrdersCount(int $ordersCount): ClientResponseDto
    {
        $this->ordersCount = $ordersCount;
        return $this;
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

    public function getStatusSymbol(): ?string
    {
        return $this->statusSymbol;
    }

    public function setStatusSymbol(?string $statusSymbol): self
    {
        $this->statusSymbol = $statusSymbol;

        return $this;
    }

    public function getStatusFormatted(): ?string
    {
        return $this->statusFormatted;
    }

    public function setStatusFormatted(?string $statusFormatted): self
    {
        $this->statusFormatted = $statusFormatted;

        return $this;
    }

    public function getStore(): ?StoreDto
    {
        return $this->store;
    }

    public function setStore(?StoreDto $store): self
    {
        $this->store = $store;

        return $this;
    }

    public function getStatusFilter(): ?int
    {
        return $this->statusFilter;
    }

    public function setStatusFilter(?int $statusFilter): self
    {
        $this->statusFilter = $statusFilter;

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

    public function getIdExternal(): ?string
    {
        return $this->idExternal;
    }

    public function setIdExternal(?string $idExternal): self
    {
        $this->idExternal = $idExternal;

        return $this;
    }

    public function getStoreSymbol(): ?string
    {
        return $this->storeSymbol;
    }

    public function setStoreSymbol(?string $storeSymbol): self
    {
        $this->storeSymbol = $storeSymbol;

        return $this;
    }


}
