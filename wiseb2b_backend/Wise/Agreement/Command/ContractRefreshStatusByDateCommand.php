<?php

namespace Wise\Agreement\Command;

use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wise\Agreement\Service\Contract\Interfaces\ContractRefreshStatusByDateServiceInterface;
use Wise\Core\Repository\RepositoryManager;
use Wise\Core\Repository\RepositoryManagerInterface;

#[AsCommand(
    name: 'wise:contract:refresh-status',
    description: 'Komenda służy do aktualizacji statusów dla umów (Contract) ze względu na ustawione daty',
    hidden: false
)]
class ContractRefreshStatusByDateCommand extends Command
{
    public function __construct(
        private readonly ContractRefreshStatusByDateServiceInterface $service,
        private readonly RepositoryManagerInterface $repositoryManager
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Rozpoczęcie aktualizacje statusów umów'
        ]);

        $this->repositoryManager->beginTransaction();

        try {
            ($this->service)();

            $this->repositoryManager->flush();
            $this->repositoryManager->commit();

        } catch (Exception $e) {
            $output->writeln([
                'Wystąpił problem podczas aktualizacji statusów umów'
            ]);
            $output->writeln([
                $e
            ]);

            $this->repositoryManager->rollback();
            $this->repositoryManager->clear();

            return Command::FAILURE;
        }

        $output->writeln([
            'Aktualizacja statusów umów zostało zakończone'
        ]);

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('Komenda służy do aktualizacji statusów dla umów (Contract)');
    }
}
