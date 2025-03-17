<?php

declare(strict_types=1);

namespace Wise\User\Domain\UserMessageSettings;

use Doctrine\ORM\Mapping as ORM;
use Wise\Core\Entity\AbstractEntity;
use Wise\User\Repository\Doctrine\MessageSettingsTranslationRepository;

/**
 * Model przechowuję tłumczenia dla pola name, nazwy powiadomień
 *
 * np: Zamówienie wysłane, Panel ofert
 */
#[ORM\Entity(repositoryClass: MessageSettingsTranslationRepository::class)]
class MessageSettingsTranslation extends AbstractEntity
{
    #[ORM\Column(length: 3)]
    protected ?string $language = null;

    #[ORM\Column]
    protected ?int $messageSettingsId = null;

    /**
     * Nazwa powiadomienia, np: Zamówienie wysłane, Panel ofert
     */
    #[ORM\Column(length: 255)]
    protected ?string $name = null;

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getMessageSettingsId(): ?int
    {
        return $this->messageSettingsId;
    }

    public function setMessageSettingsId(?int $messageSettingsId): self
    {
        $this->messageSettingsId = $messageSettingsId;

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
