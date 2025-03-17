<?php

namespace Wise\Agreement\Repository\Doctrine\ContractTranslation;

use Wise\Core\Entity\AbstractEntity;

class ContractTranslation extends AbstractEntity
{
    /**
     * Identyfikator umowy
     * @var int|null
     */
    protected ?int $contractId = null;

    /**
     * Język
     * @var string|null
     */
    protected ?string $language = null;

    /**
     * Nazwa umowy
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * Treść umowy (format HTML)
     * @var string|null
     */
    protected ?string $content = null;

    /**
     * Treść świadczenia do umowy — treść html, krótka, samo oświadczenie o akceptacji — wyświetla na froncie klienta.
     * @var string|null
     */
    protected ?string $testimony = null;

    public function getContractId(): ?int
    {
        return $this->contractId;
    }

    public function setContractId(?int $contractId): void
    {
        $this->contractId = $contractId;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getTestimony(): ?string
    {
        return $this->testimony;
    }

    public function setTestimony(?string $testimony): self
    {
        $this->testimony = $testimony;

        return $this;
    }


}
