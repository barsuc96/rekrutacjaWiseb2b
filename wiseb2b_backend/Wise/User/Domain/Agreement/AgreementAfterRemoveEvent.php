<?php

declare(strict_types=1);

namespace Wise\User\Domain\Agreement;

use Wise\Core\Domain\Event\ExternalDomainEvent;

class AgreementAfterRemoveEvent implements ExternalDomainEvent
{
    public const NAME = 'agreement.after.remove';

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
