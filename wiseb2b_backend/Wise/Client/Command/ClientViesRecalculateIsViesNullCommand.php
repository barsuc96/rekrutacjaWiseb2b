<?php

namespace Wise\Client\Command;

use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wise\Client\Service\Client\Interfaces\ListClientsServiceInterface;
use Wise\Client\Service\Client\Interfaces\VerifyClientViesInformationServiceInterface;
use Wise\Client\Service\Client\VerifyClientViesInformationServiceParams;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;

#[AsCommand(
    name: 'wise:client:vies_null_recalculate',
    description: 'Weryfikacja Vies dla wszystkich klientów gdzie informacja o vies jest równa null',
    hidden: false
)]
class ClientViesRecalculateIsViesNullCommand extends Command
{
    public function __construct(
        private readonly ListClientsServiceInterface $listClientsService,
        private readonly VerifyClientViesInformationServiceInterface $verifyClientViesInformationService,
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
                    new QueryFilter('limit', null),
                    new QueryFilter('isVies', null, QueryFilter::COMPARATOR_IS_NULL)
                ])
                ->setFields(['id' => 'id']);

            $clients = ($this->listClientsService)($params)->read();
            $progressBar = new ProgressBar($output, count($clients));

            foreach ($clients as $client) {
                try{
                    $params = new VerifyClientViesInformationServiceParams();
                    $params->setClientId($client['id']);
                    ($this->verifyClientViesInformationService)($params);
                }catch (Exception $exception){
                    $output->writeln([
                        'Wystąpił problem podczas przeliczania Vies dla klienta: ' . $client['id'] . ' -> ' . $exception->getMessage()
                    ]);
                }
                $progressBar->advance();
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
