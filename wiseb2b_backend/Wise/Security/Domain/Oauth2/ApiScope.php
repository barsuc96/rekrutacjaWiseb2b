<?php

declare(strict_types=1);

namespace Wise\Security\Domain\Oauth2;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Wise\Security\Repository\Doctrine\ApiScopeRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ApiScope extends CommonDbEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", nullable=false, options={"unsigned": true})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private ?string $name;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return $this
     */
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
