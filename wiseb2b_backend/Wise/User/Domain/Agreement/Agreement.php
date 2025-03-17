<?php

declare(strict_types=1);

namespace Wise\User\Domain\Agreement;

use Doctrine\ORM\Mapping as ORM;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Helper\Object\TranslationsHelper;
use Wise\Core\Model\Translations;
use Wise\User\Repository\Doctrine\AgreementRepository;

#[ORM\Entity(repositoryClass: AgreementRepository::class)]
class Agreement extends AbstractEntity
{
    // Id zewnętrzne zgody
    #[ORM\Column(nullable: true)]
    protected ?string $symbol = null;

    // Czy zgoda wymagana?
    #[ORM\Column]
    protected ?bool $isRequired = null;

    protected ?Translations $name = null;

    protected ?Translations $content = null;

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(?string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getIsRequired(): ?bool
    {
        return $this->isRequired;
    }

    public function setIsRequired(?bool $isRequired): self
    {
        $this->isRequired = $isRequired;

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

    public function getContent(): ?Translations
    {
        return $this->content;
    }

    public function setContent(null|Translations|array $content): self
    {
        $this->content = TranslationsHelper::convert($content);

        return $this;
    }
}
