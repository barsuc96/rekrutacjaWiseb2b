<?php

declare(strict_types=1);

namespace Wise\Client\Command;

use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wise\Client\Service\Client\Command\ViesRecalculateForClientCommand;
use Wise\Client\Service\Client\Interfaces\ListClientsServiceInterface;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\DomainEventsDispatcher;

#[AsCommand(
    name: 'wise:client:vies_recalculate',
    description: 'Weryfikacja Vies dla wszystkich klientów',
    hidden: false
)]
class ClientViesRecalculateCommand extends Command
{
    public function __construct(
        private readonly ListClientsServiceInterface $listClientsService,
        private readonly DomainEventsDispatcher $eventsDispatcher,
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Rozpoczęcie przeliczania Vies dla klientów'
        ]);

        try {
            $params = new CommonListParams();
            $params
                ->setFilters([
                    new QueryFilter('limit', null)
                ])
                ->setFields(['id' => 'id']);

            $clients = ($this->listClientsService)($params)->read();

            foreach ($clients as $client) {
                $this->eventsDispatcher->dispatchToQueue(new ViesRecalculateForClientCommand($client['id']));
            }

        } catch (Exception $e) {
            $output->writeln([
                'Wystąpił problem podczas przeliczania Vies dla klientów'
            ]);
            $output->writeln([
                $e
            ]);

            return Command::FAILURE;
        }

        $output->writeln([
            'Naliczanie Vies dla klientów zostało zakończone'
        ]);

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('Komenda służy do przeliczania/weryfikacji Vies dla klientów');
    }
}
