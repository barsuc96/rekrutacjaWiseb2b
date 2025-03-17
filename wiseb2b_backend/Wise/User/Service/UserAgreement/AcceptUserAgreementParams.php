<?php

declare(strict_types=1);

namespace Wise\User\Service\UserAgreement;

class AcceptUserAgreementParams
{
    protected ?string $agreementSymbol = null;
    protected ?int $agreementId = null;
    protected bool $granted = false;
    protected ?int $userId = null;
    protected ?int $clientId = null;

    public function getAgreementSymbol(): ?string
    {
        return $this->agreementSymbol;
    }

    public function setAgreementSymbol(?string $agreementSymbol): self
    {
        $this->agreementSymbol = $agreementSymbol;

        return $this;
    }

    public function getAgreementId(): ?int
    {
        return $this->agreementId;
    }

    public function setAgreementId(?int $agreementId): self
    {
        $this->agreementId = $agreementId;

        return $this;
    }

    public function isGranted(): bool
    {
        return $this->granted;
    }

    public function setGranted(bool $granted): self
    {
        $this->granted = $granted;

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

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }


}
