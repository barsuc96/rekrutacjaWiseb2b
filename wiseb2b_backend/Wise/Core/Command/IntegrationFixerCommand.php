<?php

namespace Wise\Core\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wise\Core\Service\Interfaces\IntegrationFixerServiceInterface;

#[AsCommand(
    name: 'wise:integration-fixer',
    description: 'Komenda służy do naprawy problemów z integracją',
    hidden: false
)]
class IntegrationFixerCommand extends Command
{
    public function __construct(
        private readonly IntegrationFixerServiceInterface $integrationFixerService
    ){
        parent::__construct();
    }
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        ($this->integrationFixerService)($output);

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('Komenda służy do naprawy problemów z integracją');
    }
}

