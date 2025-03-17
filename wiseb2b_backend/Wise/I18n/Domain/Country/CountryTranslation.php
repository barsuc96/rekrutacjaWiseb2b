<?php

declare(strict_types=1);

namespace Wise\I18n\Domain\Country;

use Doctrine\ORM\Mapping as ORM;
use Wise\Core\Entity\AbstractEntity;

#[ORM\Entity(repositoryClass: CountryTranslationRepositoryInterface::class)]
class CountryTranslation extends AbstractEntity
{
    #[ORM\Column]
    protected ?int $countryId = null;

    #[ORM\Column(length: 3)]
    protected ?string $language = null;

    #[ORM\Column(length: 255)]
    protected ?string $name = null;

    public function getCountryId(): ?int
    {
        return $this->countryId;
    }

    public function setCountryId(?int $countryId): self
    {
        $this->countryId = $countryId;

        return $this;
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
}
