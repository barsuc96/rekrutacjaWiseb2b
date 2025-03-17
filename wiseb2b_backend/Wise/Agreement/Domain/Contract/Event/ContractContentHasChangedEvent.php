<?php

namespace Wise\Agreement\Domain\Contract\Event;

use Wise\Core\Domain\Event\InternalDomainEvent;
use Wise\Core\Model\Translations;

class ContractContentHasChangedEvent implements InternalDomainEvent
{
    public const NAME = 'contract.content.has.changed';

    public function __construct(
        protected ?int $id = null,
        protected ?Translations $oldContent = null,
        protected ?array $newContent = null
    ){}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOldContent(): ?Translations
    {
        return $this->oldContent;
    }

    public function getNewContent(): ?array
    {
        return $this->newContent;
    }

    public static function getName(): ?string
    {
        return self::NAME;
    }
}
