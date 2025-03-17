<?php

declare(strict_types=1);

namespace Wise\MultiStore\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Exception;
use Wise\MultiStore\Service\CopyConfigurationFromOneStoreToOtherStoreParams;
use Wise\MultiStore\Service\Interfaces\CopyConfigurationFromOneStoreToOtherStoreServiceInterface;

#[AsCommand(
    name: 'wise:copy-configuration:multistore',
    description: 'Komenda służąca do kopiowania konfiguracji systemu z jednego sklepu do drugiego sklepu',
    hidden: false
)]
class CopyConfigurationFromOneStoreToOtherStoreCommand extends Command
{
    public function __construct(
        private readonly CopyConfigurationFromOneStoreToOtherStoreServiceInterface $service,
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Rozpoczęcie konfiguracji systemu pod MuliStore'
        ]);

        try {
            $params = new CopyConfigurationFromOneStoreToOtherStoreParams();
            $params->setFromStoreSymbol($input->getArgument('fromStoreSymbol'));
            $params->setToStoreSymbol($input->getArgument('toStoreSymbol'));
            $params->setSuffix($input->getArgument('suffix'));
            $params->setFlags($input->getArgument('flags'));

            ($this->service)($params);

        } catch (Exception $e) {
            $output->writeln([
                'Wystąpił problem podczas konfiguracji systemu pod MuliStore'
            ]);
            $output->writeln([
                $e
            ]);

            return Command::FAILURE;
        }

        $output->writeln([
            'Konfiguracja systemu pod MuliStore została zakończona'
        ]);

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('Komenda służąca do kopiowania konfiguracji systemu z jednego sklepu do drugiego sklepu');

        $this->addArgument('fromStoreSymbol', InputArgument::REQUIRED, 'Symbol sklepu z którego kopiujemy konfigurację');
        $this->addArgument('toStoreSymbol', InputArgument::REQUIRED, 'Symbol sklepu do którego kopiujemy konfigurację');
        $this->addArgument('suffix', InputArgument::REQUIRED, 'Sufiks dla tabeli');
        $this->addArgument('flags', InputArgument::REQUIRED, 'Flagi dla kopiowania konfiguracji');
    }
}
