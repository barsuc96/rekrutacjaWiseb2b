<?php

namespace Wise\User\Domain\UserMessageSettings;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class UserMessageSettingsHasCreatedEvent implements ExternalDomainEvent
{
    public const NAME = 'user_message_settings.created';

    public function __construct(
        private readonly int $id,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public static function getName(): ?string
    {
        return self::NAME;
    }
}