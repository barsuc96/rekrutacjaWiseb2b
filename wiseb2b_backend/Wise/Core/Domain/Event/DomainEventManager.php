<?php

declare(strict_types=1);

namespace Wise\Core\Domain\Event;

use Wise\Core\Exception\UnspecifiedDomainEventType;

class DomainEventManager
{
    /** @var array<DomainEvent> */
    private array $events = [];
    private static ?self $instance = null;

    private function __construct()
    {
    }

    public static function instance(): self
    {
        if (null === static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    public function post(DomainEvent $event): void
    {
        if($event instanceof InternalDomainEvent) {
            $this->postInternal($event);
        } else if ($event instanceof ExternalDomainEvent) {
            $this->postExternal($event);
        } else {
            throw new UnspecifiedDomainEventType("You need to use InternalDomainEvent or ExternalDomainEvent");
        }
    }

    private function postInternal(InternalDomainEvent $event): void {
        $this->events[] = $event;
    }

    private function postExternal(ExternalDomainEvent $event): void {
        $this->events[] = $event;
    }

    public function getFirst(): ?DomainEvent
    {
        return array_shift($this->events);
    }

    public function getFirstInternal(): ?InternalDomainEvent
    {
        foreach ($this->events as $key => $event) {
            if ($event instanceof InternalDomainEvent) {
                unset($this->events[$key]);

                return $event;
            }
        }

        return null;
    }

    public function clear(): void
    {
        $this->events = [];
    }
}
