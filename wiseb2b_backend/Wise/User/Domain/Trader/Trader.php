<?php

declare(strict_types=1);

namespace Wise\User\Domain\Trader;

use Doctrine\ORM\Mapping as ORM;
use Wise\Core\Entity\AbstractEntity;
use Wise\User\Repository\Doctrine\TraderRepository;

#[ORM\Entity(repositoryClass: TraderRepository::class)]
#[ORM\Index(columns: ['email'])]
#[ORM\Index(columns: ['is_default'])]
class Trader extends AbstractEntity
{
    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $idExternal = null;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $lastName = null;

    #[ORM\Column(length: 60, nullable: true)]
    protected ?string $email = null;

    #[ORM\Column(length: 60, nullable: true)]
    protected ?string $phone = null;

    #[ORM\Column(nullable: true)]
    protected ?bool $isDefault = null;

    /**
     * Wskazanie na encje użytkownika który jest danym traderem
     */
    #[ORM\Column(nullable: true)]
    protected ?int $userId = null;

    public function getName(): string
    {
        return $this->firstName.' '.$this->lastName;
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

    public function getIsDefault(): ?bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(?bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
