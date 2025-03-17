<?php

declare(strict_types=1);

namespace Wise\Security\Domain\Oauth2;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks
 */
class CommonDbEntity
{
    /**
     * @var DateTime|null
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected ?DateTime $createdAt;

    /**
     * @var DateTime|null
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected ?DateTime $updatedAt;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist(): void
    {
        $this->setCreatedAt();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate(): void
    {
        $this->setUpdatedAt();
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime|null $createdAt
     * @return $this
     */
    public function setCreatedAt(?DateTime $createdAt = null): self
    {
        $this->createdAt = $createdAt ?? new DateTime("now");

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime|null $updatedAt
     * @return $this
     */
    public function setUpdatedAt(?DateTime $updatedAt = null): self
    {
        $this->updatedAt = $updatedAt ?? new DateTime("now");

        return $this;
    }
}
