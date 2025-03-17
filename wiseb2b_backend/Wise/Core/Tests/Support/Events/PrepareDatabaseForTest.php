<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Support\Events;

use Codeception\Event\SuiteEvent;
use Codeception\Events;
use Codeception\Extension;
use Codeception\Module\Symfony;

final class PrepareDatabaseForTest extends Extension
{
    public static array $events = [
        Events::SUITE_INIT => 'createDatabase',
    ];

    public function createDatabase(SuiteEvent $event): void
    {
        if (in_array('Symfony', array_keys($event->getSuite()->getModules()), true) === false) {
            $this->writeln('<error>Symfony module is not enabled. Skip database seeding.</error>');
            return;
        }

        /** @var Symfony $symfony */
        $symfony = $event->getSuite()->getModules()['Symfony'];
        $symfony->runSymfonyConsoleCommand('doctrine:database:create', ['--if-not-exists' => true]);
        $symfony->runSymfonyConsoleCommand('doctrine:schema:drop', ['--force' => true, '--full-database' => true]);
        $symfony->runSymfonyConsoleCommand('doctrine:schema:update', ['--force' => true, '--complete' => true]);
        $symfony->runSymfonyConsoleCommand('doctrine:migrations:migrate');
        $symfony->runSymfonyConsoleCommand('doctrine:fixtures:load', ['--append' => true]);
    }
}
