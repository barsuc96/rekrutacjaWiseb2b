<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Dto\Users;

use OpenApi\Attributes as OA;
use Wise\Core\ApiAdmin\Dto\DateInsertUpdateDtoTrait;
use Wise\Core\Dto\AbstractResponseDto;
use DateTimeInterface;

class GetUserResponseDto extends AbstractResponseDto
{
    use DateInsertUpdateDtoTrait;

    #[OA\Property(
        description: 'Zewnętrzne ID',
        example: 'UUID-123',
    )]
    protected ?string $id;

    #[OA\Property(
        description: 'Wewnętrzne ID',
        example: 1,
    )]
    protected ?int $internalId;

    #[OA\Property(
        description: 'Zewnętrzne ID klienta',
        example: 'UUID-123',
    )]
    protected ?string $clientId;

    #[OA\Property(
        description: 'Wewnętrzne ID klienta',
        example: 1,
    )]
    protected ?int $clientInternalId;

    #[OA\Property(
        description: 'Zewnętrzne ID roli',
        example: 'UUID-123',
    )]
    protected ?string $roleId;

    #[OA\Property(
        description: 'Wewnętrzne ID roli',
        example: 1,
    )]
    protected ?int $roleInternalId;

    #[OA\Property(
        description: 'Zewnętrzne ID tradera',
        example: 'UUID-123',
    )]
    protected ?string $traderId;

    #[OA\Property(
        description: 'Wewnętrzne ID tradera',
        example: 1,
    )]
    protected ?int $traderInternalId;

    #[OA\Property(
        description: 'Login użytkownika',
        example: 'string',
    )]
    protected ?string $login;

    #[OA\Property(
        description: 'Hasło użytkownika',
        example: 'string',
    )]
    protected ?string $password;

    #[OA\Property(
        description: 'Data utworzenia',
        example: '2023-02-01 00:00:00',
    )]
    protected ?DateTimeInterface $createDate;

    #[OA\Property(
        description: 'Imię',
        example: 'Jan',
    )]
    protected ?string $firstName;

    #[OA\Property(
        description: 'Nazwisko',
        example: 'Nowak',
    )]
    protected ?string $lastName;

    #[OA\Property(
        description: 'Adres email',
        example: 'example@example.com',
    )]
    protected ?string $email;

    #[OA\Property(
        description: 'Nr telefonu',
        example: '+48777777777',
    )]
    protected ?string $phone;

    #[OA\Property(
        description: 'Czy użytkownik aktywny?',
        example: true,
    )]
    protected ?bool $isActive;

    #[OA\Property(
        description: 'Czy użytkownik potwierdził adres e-mail?',
        example: true,
    )]
    protected ?bool $mailConfirmed;

    #[OA\Property(
        description: 'Identyfikator sklepu',
        example: 1,
    )]
    protected ?int $storeId;

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

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function setClientId(?string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getClientInternalId(): ?int
    {
        return $this->clientInternalId;
    }

    public function setClientInternalId(?int $clientInternalId): self
    {
        $this->clientInternalId = $clientInternalId;

        return $this;
    }

    public function getRoleId(): ?string
    {
        return $this->roleId;
    }

    public function setRoleId(?string $roleId): self
    {
        $this->roleId = $roleId;

        return $this;
    }

    public function getRoleInternalId(): ?int
    {
        return $this->roleInternalId;
    }

    public function setRoleInternalId(?int $roleInternalId): self
    {
        $this->roleInternalId = $roleInternalId;

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

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCreateDate(): ?DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(?DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getMailConfirmed(): ?bool
    {
        return $this->mailConfirmed;
    }

    public function setMailConfirmed(?bool $mailConfirmed): self
    {
        $this->mailConfirmed = $mailConfirmed;

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
