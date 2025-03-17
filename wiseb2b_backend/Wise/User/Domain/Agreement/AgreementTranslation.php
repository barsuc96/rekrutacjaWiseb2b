<?php

declare(strict_types=1);

namespace Wise\User\Domain\Agreement;

use Doctrine\ORM\Mapping as ORM;
use Wise\Core\Entity\AbstractEntity;
use Wise\User\Repository\Doctrine\AgreementTranslationRepository;

#[ORM\Entity(repositoryClass: AgreementTranslationRepository::class)]
class AgreementTranslation extends AbstractEntity
{
    #[ORM\Column(length: 3)]
    protected ?string $language = null;

    #[ORM\Column]
    protected ?int $agreementId = null;

    // Nazwa zgody np: "Zgoda na marketing"
    #[ORM\Column(length: 255)]
    protected ?string $name = null;

    // Treść zgody np: "Wyrażam zgodę na przetwarzanie moich danych osobowych przez.... "
    #[ORM\Column(length: 8000, nullable: true)]
    protected ?string $content = null;

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

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
}
