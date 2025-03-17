<?php

namespace Wise\Agreement\Domain\ContractAgreement;

use Wise\Agreement\Domain\ContractAgreement\Event\ContractAgreementHasChangedEvent;
use Wise\Core\Domain\Event\DomainEventManager;
use Wise\Core\Entity\AbstractEntity;

/**
 * Klasa reprezentująca zgodę wyrażoną przez użytkownika
 */
class ContractAgreement extends AbstractEntity
{
    /**
     * Id zewnętrzne (z systemu klienta)
     * @var string|null
     */
    protected ?string $idExternal;

    /**
     * Identyfikator użytkownika
     * @var int|null
     */
    protected ?int $userId = null;

    /**
     * Identyfikator klienta
     * @var int|null
     */
    protected ?int $clientId = null;

    /**
     * Identyfikator koszyka
     * @var int|null
     */
    protected ?int $cartId = null;

    /**
     * Identyfikator umowy
     * @var int|null
     */
    protected ?int $contractId = null;

    /**
     * Kontekst wyrażenia zgody (miejsce w serwisie - symbol)
     * @var string|null
     */
    protected ?string $contextAgreement = null;

    /**
     * IP z którego wyrażono zgodę
     * @var string|null
     */
    protected ?string $agreeIp = null;

    /**
     * Data akceptacji zgody
     * @var \DateTimeInterface|null
     */
    protected ?\DateTimeInterface $agreeDate = null;

    /**
     * IP z którego zrezygnowano ze zgody
     * @var string|null
     */
    protected ?string $disagreeIp = null;

    /**
     * Data rezygnacji ze zgody
     * @var \DateTimeInterface|null
     */
    protected ?\DateTimeInterface $disagreeDate = null;

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

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

    public function getContractId(): ?int
    {
        return $this->contractId;
    }

    public function setContractId(?int $contractId): self
    {
        $this->contractId = $contractId;

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

    public function getAgreeIp(): ?string
    {
        return $this->agreeIp;
    }

    public function setAgreeIp(?string $agreeIp): self
    {
        $this->agreeIp = $agreeIp;

        return $this;
    }

    public function getAgreeDate(): ?\DateTimeInterface
    {
        return $this->agreeDate;
    }

    public function setAgreeDate(?\DateTimeInterface $agreeDate): self
    {
        $this->agreeDate = $agreeDate;

        return $this;
    }

    public function getDisagreeIp(): ?string
    {
        return $this->disagreeIp;
    }

    public function setDisagreeIp(?string $disagreeIp): self
    {
        $this->disagreeIp = $disagreeIp;

        return $this;
    }

    public function getDisagreeDate(): ?\DateTimeInterface
    {
        return $this->disagreeDate;
    }

    public function setDisagreeDate(?\DateTimeInterface $disagreeDate): self
    {
        $this->disagreeDate = $disagreeDate;

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

    protected function entityHasChanged(string $newHash): void
    {
        parent::entityHasChanged($newHash);

        DomainEventManager::instance()->post(new ContractAgreementHasChangedEvent($this->getId()));
    }
}
