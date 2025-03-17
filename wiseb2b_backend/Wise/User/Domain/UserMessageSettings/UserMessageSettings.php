<?php

declare(strict_types=1);

namespace Wise\User\Domain\UserMessageSettings;

use Doctrine\ORM\Mapping as ORM;
use Wise\Core\Entity\AbstractEntity;
use Wise\User\Repository\Doctrine\UserMessageSettingsRepository;

/**
 * Klasa przechowuje ustawienia powiadomień dla wybranego użytkownika
 *
 * Wpis w encji oznacza, że użytkownik ma włączone dane powiadomienia
 */
#[ORM\Entity(repositoryClass: UserMessageSettingsRepository::class)]
class UserMessageSettings extends AbstractEntity
{
    /**
     * Id użytkownika
     */
    #[ORM\Column(nullable: true)]
    protected ?int $userId;

    /**
     * Id użytkownika
     */
    #[ORM\Column(nullable: true)]
    protected ?int $clientId;

    /**
     * ID ustawień powiadomień
     */
    #[ORM\Column]
    protected int $messageSettingsId;

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

    public function getMessageSettingsId(): int
    {
        return $this->messageSettingsId;
    }

    public function setMessageSettingsId(int $messageSettingsId): self
    {
        $this->messageSettingsId = $messageSettingsId;

        return $this;
    }
}
