<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use DateTimeInterface;
use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class UserAgreementsResponseDto  extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Identyfikator obiektu',
        example: 1,
    )]
    protected int $id;

    #[OA\Property(
        description: 'Ip użytkownika który ustawił zgodę',
        example: '192.168.1.1',
    )]
    protected string $ipAddress;

    #[OA\Property(
        description: 'Data zmiany zgody',
        example: '2023-01-01 00:00:01'
    )]
    protected DateTimeInterface $date;

    #[OA\Property(
        description: 'Opis zgody',
        example: 'example',
    )]
    protected string $content;

    #[OA\Property(
        description: 'Typy zgody',
        example: 2,
    )]
    protected int $type;

    #[OA\Property(
        description: 'Czy zgoda wymagana?',
        example: true,
    )]
    protected bool $necessary;

    #[OA\Property(
        description: 'Czy zgoda zakceptowana czy odrzucona?',
        example: true,
    )]
    protected bool $accepted;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
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

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): self
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
