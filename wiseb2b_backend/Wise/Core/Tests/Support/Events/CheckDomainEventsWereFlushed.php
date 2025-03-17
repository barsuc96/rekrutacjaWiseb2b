<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Support\Events;

use Codeception\Events;
use Codeception\Extension;
use Wise\Core\Domain\Event\DomainEventManager;

final class CheckDomainEventsWereFlushed extends Extension
{
    public static array $events = [
        Events::TEST_AFTER => 'checkEventStore',
    ];

    public function checkEventStore(): void
    {
        $eventsManager = DomainEventManager::instance();

        $events = [];
        while ($event = $eventsManager->getFirst()) {
            $events[] = $event;
        }
        if ($events) {
            $this->writeln("<error>Domain event was not flushed before end of test.</error>");
            $this->writeln("<info>List of events:</info>");
            foreach ($events as $event) {
                $this->writeln("<info>{$event->getName()}</info>");
            }
            throw new \RuntimeException();
        }
    }
}
