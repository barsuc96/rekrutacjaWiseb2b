<?php

namespace Wise\I18n\Domain\Country;

use Doctrine\ORM\Mapping as ORM;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Helper\Object\TranslationsHelper;
use Wise\Core\Model\Translations;

#[ORM\Entity(repositoryClass: CountryRepositoryInterface::class)]
class Country extends AbstractEntity
{
    #[ORM\Column(length: 3)]
    protected ?string $idExternal = null;

    protected ?Translations $name = null;

    #[ORM\Column(type: 'boolean', nullable: true, options: ['default' => false])]
    protected ?bool $inEuropeanUnion = null;

    public function getIdExternal(): ?string
    {
        return $this->idExternal;
    }

    public function setIdExternal(?string $idExternal): self
    {
        $this->idExternal = $idExternal;

        return $this;
    }

    public function getName(): ?Translations
    {
        return $this->name;
    }

    public function setName(null|Translations|array $name): self
    {
        $this->name = TranslationsHelper::convert($name);

        return $this;
    }

    public function getInEuropeanUnion(): ?bool
    {
        return $this->inEuropeanUnion;
    }

    public function setInEuropeanUnion(?bool $inEuropeanUnion): self
    {
        $this->inEuropeanUnion = $inEuropeanUnion;

        return $this;
    }
}
