<?php

declare(strict_types=1);

namespace Wise\Core\Service;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Wise\Core\Domain\Event\DomainEvent;
use Wise\Core\Domain\Event\DomainEventManager;

class DomainEventsDispatcher
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private MessageBusInterface $bus,
    ) {}

    public function flushInternalEvents(): void
    {
        $eventsManager = DomainEventManager::instance();

        while ($event = $eventsManager->getFirstInternal()) {
            $this->eventDispatcher->dispatch($event, $event::getName());
        }
    }

    public function flush(): void
    {
        $eventsManager = DomainEventManager::instance();

        while ($event = $eventsManager->getFirst()) {
            $this->eventDispatcher->dispatch($event, $event::getName());
        }
    }

    public function dispatchEvent(DomainEvent $event): void
    {
        $eventName = null;

        if (method_exists($event, 'getName')) {
            $eventName = $event::getName();
        }

        $this->eventDispatcher->dispatch($event, $eventName);
    }

    public function dispatchToQueue(mixed $object, array $stamps = []): void
    {
        $this->bus->dispatch($object, $stamps);
    }

    public function clear(): void
    {
        $eventsManager = DomainEventManager::instance();
        $eventsManager->clear();
    }
}
