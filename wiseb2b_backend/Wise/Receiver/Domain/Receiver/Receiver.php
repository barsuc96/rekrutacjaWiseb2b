<?php

declare(strict_types=1);

namespace Wise\Receiver\Domain\Receiver;

use Symfony\Component\Validator\Constraints as Assert;
use Wise\Core\Domain\Event\DomainEventManager;
use Wise\Core\Validator\Constraints as WiseAssert;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Model\Address;
use Wise\Core\Validator\Enum\ConstraintTypeEnum;
use Wise\Receiver\Domain\Receiver\Events\ReceiverHasChangedEvent;

class Receiver extends AbstractEntity
{
    protected ?string $idExternal = null;

    protected ?int $clientId = null;

    #[WiseAssert\NotBlank(
        message: "DOM//Pole nie możę być puste",
        payload: ["constraintType" => ConstraintTypeEnum::ERROR],
    )]
    #[WiseAssert\Length(
        min: 3,
        minMessage: "DOM//Nazwa nie może zajmować mniej niż 3 znaki",
        constraintType: ConstraintTypeEnum::ERROR
    )]
//    #[WiseAssert\Length(
//        min: 5,
//        minMessage: "DOM//Możliwie że nazwa jest za krótka",
//        constraintType: ConstraintTypeEnum::WARNING
//    )]
    protected ?string $name = null;

    protected ?string $type = null;

    #[Assert\Valid()]
    protected ?Address $deliveryAddress = null;

    protected ?string $firstName = null;

    #[WiseAssert\NotBlank(
        payload: ["constraintType" => ConstraintTypeEnum::ERROR],
    )]
    protected ?string $lastName = null;

    protected ?string $email = null;

    protected ?string $phone = null;

    protected ?bool $isDefault = null;

    public function getIdExternal(): ?string
    {
        return $this->idExternal;
    }

    public function setIdExternal(?string $idExternal): self
    {
        $this->idExternal = $idExternal;
        return $this;
    }

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;
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

    public function getDeliveryAddress(): ?Address
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(?Address $deliveryAddress): self
    {
        $this->deliveryAddress = $deliveryAddress;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    protected function entityHasChanged(string $newHash): void
    {
        parent::entityHasChanged($newHash);

        DomainEventManager::instance()->post(new ReceiverHasChangedEvent($this->getId()));
    }
}
