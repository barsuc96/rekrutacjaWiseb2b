<?php

declare(strict_types=1);

namespace Wise\User\Domain\UserRelation;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Wise\Core\Entity\AbstractEntity;
use Wise\User\Repository\Doctrine\UserRelationRepository;

#[ORM\Entity(repositoryClass: UserRelationRepository::class)]
class UserRelation extends AbstractEntity
{
    #[ORM\Column(nullable: true)]
    protected ?string $idExternal = null;

    #[ORM\Column(nullable: true)]
    protected ?int $userId = null;

    #[ORM\Column(nullable: true)]
    protected ?int $relatedUserId = null;

    #[ORM\Column(length: 32, nullable: true)]
    protected ?string $userRole = null;

    #[ORM\Column(nullable: true)]
    protected ?int $relationsLevel = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTimeInterface $changeDate = null;

    public function getIdExternal(): ?string
    {
        return $this->idExternal;
    }

    public function setIdExternal(?string $idExternal): self
    {
        $this->idExternal = $idExternal;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getRelatedUserId(): ?int
    {
        return $this->relatedUserId;
    }

    public function setRelatedUserId(?int $relatedUserId): self
    {
        $this->relatedUserId = $relatedUserId;

        return $this;
    }

    public function getUserRole(): ?string
    {
        return $this->userRole;
    }

    public function setUserRole(?string $userRole): self
    {
        $this->userRole = $userRole;

        return $this;
    }

    public function getRelationsLevel(): ?int
    {
        return $this->relationsLevel;
    }

    public function setRelationsLevel(?int $relationsLevel): self
    {
        $this->relationsLevel = $relationsLevel;

        return $this;
    }

    public function getChangeDate(): ?DateTimeInterface
    {
        return $this->changeDate;
    }

    public function setChangeDate(?DateTimeInterface $changeDate): self
    {
        $this->changeDate = $changeDate;

        return $this;
    }
}
