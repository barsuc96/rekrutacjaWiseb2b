<?php

namespace Wise\Core\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'wise:replication-request:stats-send',
    description: 'Komenda służy do wysyłki statystyk requestów z tabeli replication_request',
    hidden: false
)]
class ReplicationRequestStatsCommand extends Command
{
    public function __construct(
    ){
        parent::__construct();
    }
    public function execute(InputInterface $input, OutputInterface $output): int
    {


        return Command::SUCCESS;
    }
}

