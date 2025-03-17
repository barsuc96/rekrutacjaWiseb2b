<?php

declare(strict_types=1);

namespace Wise\User\Domain\UserMessageSettings;

use Doctrine\ORM\Mapping as ORM;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Model\Translations;
use Wise\User\Repository\Doctrine\MessageSettingsRepository;

/**
 * Encja używana do przechowywania dostępnych, różnych wiadomości do ustawienia przez użytkownika
 */
#[ORM\Entity(repositoryClass: MessageSettingsRepository::class)]
class MessageSettings extends AbstractEntity
{
    /**
     * Nazwa ustawień, pobierana według aktualnego języka
     */
    protected ?Translations $name = null;

    public function getName(): ?Translations
    {
        return $this->name;
    }

    public function setName(?Translations $name): self
    {
        $this->name = $name;

        return $this;
    }
}
