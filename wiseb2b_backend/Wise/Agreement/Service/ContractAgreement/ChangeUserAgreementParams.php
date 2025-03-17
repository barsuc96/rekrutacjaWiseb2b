<?php

namespace Wise\Agreement\Service\ContractAgreement;

class ChangeUserAgreementParams
{
    public const TYPE_AGREE = 'agree';
    public const TYPE_DISAGREE = 'disagree';
    public const TYPE_TOGGLE = null;

    /**
     * Identyfikator umowy
     * @var int|null
     */
    private ?int $contractId = null;

    /**
     * Identyfikator użytkownika
     * @var int|null
     */
    private ?int $userId = null;

    /**
     * Identyfikator klienta
     * @var int|null
     */
    private ?int $clientId = null;

    /**
     * Identyfikator koszyka
     * @var int|null
     */
    private ?int $cartId = null;

    /**
     * Rodzaj pozwolenia
     * @var string|null
     */
    private ?string $type = null;

    /**
     * Kontekst umowy
     * @var string|null
     */
    private ?string $contextAgreement = null;

    /**
     * @var bool Czy pominąć walidację wymagań
     */
    private bool $skipRequirementValidation = false;

    /**
     * Czy można nie zgodzić się na wymaganą umowę
     * @var bool
     */
    private bool $canDisagreeRequiredContract = false;

    public function getContractId(): ?int
    {
        return $this->contractId;
    }

    public function setContractId(?int $contractId): self
    {
        $this->contractId = $contractId;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getContextAgreement(): ?string
    {
        return $this->contextAgreement;
    }

    public function setContextAgreement(?string $contextAgreement): self
    {
        $this->contextAgreement = $contextAgreement;

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

    public function getCartId(): ?int
    {
        return $this->cartId;
    }

    public function setCartId(?int $cartId): self
    {
        $this->cartId = $cartId;

        return $this;
    }

    public function isSkipRequirementValidation(): bool
    {
        return $this->skipRequirementValidation;
    }

    public function setSkipRequirementValidation(bool $skipRequirementValidation): self
    {
        $this->skipRequirementValidation = $skipRequirementValidation;

        return $this;
    }

    public function isCanDisagreeRequiredContract(): bool
    {
        return $this->canDisagreeRequiredContract;
    }

    public function setCanDisagreeRequiredContract(bool $canDisagreeRequiredContract): self
    {
        $this->canDisagreeRequiredContract = $canDisagreeRequiredContract;

        return $this;
    }
}
