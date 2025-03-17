<?php

declare(strict_types=1);

namespace Wise\User\Domain\UserAgreement;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class UserAgreementAfterRemoveEvent implements ExternalDomainEvent
{
    public const NAME = 'user_agreement.after.remove';

    public function __construct(
        protected int $id
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
