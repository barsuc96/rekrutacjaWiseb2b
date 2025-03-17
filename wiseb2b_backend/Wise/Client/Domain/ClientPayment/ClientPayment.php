<?php

declare(strict_types=1);

namespace Wise\Client\Domain\ClientPayment;

use DateTimeInterface;
use Wise\Core\Entity\AbstractEntity;

class ClientPayment extends AbstractEntity
{
    protected ?int $clientId = null;

    protected ?int $paymentMethodId = null;

    protected ?int $clientDocumentId = null;

    protected ?string $payerName = null;

    protected ?string $payerStreet = null;

    protected ?string $payerHouseNumber = null;

    protected ?string $payerApartmentNumber = null;

    protected ?string $payerPostalCode = null;

    protected ?string $payerCity = null;

    protected ?string $payerCountryCode = null;

    protected ?string $payerEmail = null;

    protected ?DateTimeInterface $deadline = null;

    protected ?int $status = null;

    protected ?float $valueNet = null;

    protected ?float $valueGross = null;

    protected ?string $currency = null;

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getPaymentMethodId(): ?int
    {
        return $this->paymentMethodId;
    }

    public function setPaymentMethodId(?int $paymentMethodId): self
    {
        $this->paymentMethodId = $paymentMethodId;

        return $this;
    }

    public function getClientDocumentId(): ?int
    {
        return $this->clientDocumentId;
    }

    public function setClientDocumentId(?int $clientDocumentId): self
    {
        $this->clientDocumentId = $clientDocumentId;

        return $this;
    }

    public function getPayerName(): ?string
    {
        return $this->payerName;
    }

    public function setPayerName(?string $payerName): self
    {
        $this->payerName = $payerName;

        return $this;
    }

    public function getPayerStreet(): ?string
    {
        return $this->payerStreet;
    }

    public function setPayerStreet(?string $payerStreet): self
    {
        $this->payerStreet = $payerStreet;

        return $this;
    }

    public function getPayerHouseNumber(): ?string
    {
        return $this->payerHouseNumber;
    }

    public function setPayerHouseNumber(?string $payerHouseNumber): self
    {
        $this->payerHouseNumber = $payerHouseNumber;

        return $this;
    }

    public function getPayerApartmentNumber(): ?string
    {
        return $this->payerApartmentNumber;
    }

    public function setPayerApartmentNumber(?string $payerApartmentNumber): self
    {
        $this->payerApartmentNumber = $payerApartmentNumber;

        return $this;
    }

    public function getPayerPostalCode(): ?string
    {
        return $this->payerPostalCode;
    }

    public function setPayerPostalCode(?string $payerPostalCode): self
    {
        $this->payerPostalCode = $payerPostalCode;

        return $this;
    }

    public function getPayerCity(): ?string
    {
        return $this->payerCity;
    }

    public function setPayerCity(?string $payerCity): self
    {
        $this->payerCity = $payerCity;

        return $this;
    }

    public function getPayerCountryCode(): ?string
    {
        return $this->payerCountryCode;
    }

    public function setPayerCountryCode(?string $payerCountryCode): self
    {
        $this->payerCountryCode = $payerCountryCode;

        return $this;
    }

    public function getPayerEmail(): ?string
    {
        return $this->payerEmail;
    }

    public function setPayerEmail(?string $payerEmail): self
    {
        $this->payerEmail = $payerEmail;

        return $this;
    }

    public function getDeadline(): ?DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(?DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;

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

    public function getValueNet(): ?float
    {
        return $this->valueNet;
    }

    public function setValueNet(?float $valueNet): self
    {
        $this->valueNet = $valueNet;

        return $this;
    }

    public function getValueGross(): ?float
    {
        return $this->valueGross;
    }

    public function setValueGross(?float $valueGross): self
    {
        $this->valueGross = $valueGross;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}
