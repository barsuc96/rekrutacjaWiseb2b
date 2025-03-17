<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use OpenApi\Attributes as OA;

class AgreementsDto
{
    #[OA\Property(
        description: 'Adres IP',
        example: '192.168.1.1',
    )]
    protected string $ipAddress;

    #[OA\Property(
        description: 'Data zgody',
        example: '2023-02-20 00:00:00',
    )]
    protected \DateTimeInterface $date;

    #[OA\Property(
        description: 'Treść zgody',
        example: 'example',
    )]
    protected string $content;

    #[OA\Property(
        description: 'Typ zgody',
        example: 'example',
    )]
    protected string $type;

    #[OA\Property(
        description: 'Czy zgoda wymagana',
        example: true,
    )]
    protected bool $necessary;

    #[OA\Property(
        description: 'Czy zgoda zakceptowana',
        example: true,
    )]
    protected bool $accepted;

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isNecessary(): bool
    {
        return $this->necessary;
    }

    public function setNecessary(bool $necessary): self
    {
        $this->necessary = $necessary;

        return $this;
    }

    public function isAccepted(): bool
    {
        return $this->accepted;
    }

    public function setAccepted(bool $accepted): self
    {
        $this->accepted = $accepted;

        return $this;
    }
}
