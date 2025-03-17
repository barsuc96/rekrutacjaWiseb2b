<?php

declare(strict_types=1);

namespace Wise\User\Domain\UserLoginHistory;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Wise\Core\Entity\AbstractEntity;
use Wise\User\Repository\Doctrine\UserLoginHistoryRepository;

#[ORM\Entity(repositoryClass: UserLoginHistoryRepository::class)]
class UserLoginHistory extends AbstractEntity
{
    #[ORM\Column(nullable: true)]
    protected ?int $userId = null;

    #[ORM\Column(length: 60, nullable: true)]
    protected ?string $ip = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTimeInterface $loginDate = null;

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getLoginDate(): ?DateTimeInterface
    {
        return $this->loginDate;
    }

    public function setLoginDate(?DateTimeInterface $loginDate): self
    {
        $this->loginDate = $loginDate;

        return $this;
    }
}
