<?php

declare(strict_types=1);


namespace Wise\Core\Command\Admin;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Wise\Core\ApiAdmin\ServiceInterface\Admin\ReplicationRequestObjectRetryServiceInterface;
use Wise\Core\ApiAdmin\ServiceInterface\Admin\ReplicationRequestRetryServiceInterface;

/**
 * Komenda do powtórzenia requestu replikacji
 */
#[AsCommand(
    name: 'wise:replication:retry',
    description: 'Retry replication request'
)]
class ReplicationRequestRetryCommand extends Command
{
    protected static $defaultName = 'wise:replication:retry';

    public function __construct(
        private ReplicationRequestRetryServiceInterface $replicationRequestRetryService,
        private ReplicationRequestObjectRetryServiceInterface $replicationRequestObjectRetryService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Retry replication request');
        $this->addOption('request_uuid', 'u', InputOption::VALUE_REQUIRED, 'Request uuid');
        $this->addOption('object_id', 'o', InputOption::VALUE_OPTIONAL, 'Object id');
        $this->addUsage('--request_uuid=9598a7ba-7ab7-423c-b2af-e771d6d03e64 --object_id=1');
        $this->addUsage('-u 9598a7ba-7ab7-423c-b2af-e771d6d03e64 -o 1');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $requestUuid = $input->getOption('request_uuid');

        if($input->getOption('object_id')) {
            $objectId = (int) $input->getOption('object_id');
            $result = $this->replicationRequestObjectRetryService->retry($requestUuid, $objectId);
            $output->writeln($result);
            return Command::SUCCESS;
        }

        $result = $this->replicationRequestRetryService->retry($requestUuid);
        $output->writeln($result);
        return Command::SUCCESS;
    }
}