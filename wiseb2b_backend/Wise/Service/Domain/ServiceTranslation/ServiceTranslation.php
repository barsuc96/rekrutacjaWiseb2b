<?php

declare(strict_types=1);

namespace Wise\Service\Domain\ServiceTranslation;

use Wise\Core\Entity\AbstractEntity;

class ServiceTranslation extends AbstractEntity
{
    protected ?int $serviceId = null;

    protected ?string $language = null;

    protected ?string $name = null;

    protected ?string $description = null;

    public function getServiceId(): ?int
    {
        return $this->serviceId;
    }

    public function setServiceId(int $serviceId): self
    {
        $this->serviceId = $serviceId;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
