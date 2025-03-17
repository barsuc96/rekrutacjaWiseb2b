<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class UserMessageSettingsResponseDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Identyfikator wiadomości',
        example: 1,
    )]
    protected int $id;

    #[OA\Property(
        description: 'Identyfikator ustawienia',
        example: 4,
    )]
    protected int $messageSettingsId;

    #[OA\Property(
        description: 'Zawartość wiadomości',
        example: 'Lorem ipsum.',
    )]
    protected string $name;

    #[OA\Property(
        description: 'Czy wiadoomość włączona/wyłączona?',
        example: true,
    )]
    protected bool $enabled;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

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
