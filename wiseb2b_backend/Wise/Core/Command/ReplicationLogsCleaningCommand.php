<?php

namespace Wise\Core\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\Assert\Assert;
use Wise\Core\Service\Interfaces\ReplicationLogsCleaningServiceInterface;

#[AsCommand(
    name: 'wise:replication:clean-logs',
    description: 'Komenda służy do usuwania starych logów replikacji z tabel replication_request i replication_object ',
    hidden: false
)]
class ReplicationLogsCleaningCommand extends Command
{
    protected string $endpoint = '';
    protected ?string $method = null;
    protected int $olderThanHours = 72;

    public function __construct(
        private readonly ReplicationLogsCleaningServiceInterface $service
    ){
        parent::__construct();
    }
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->prepareArguments($input);

        ($this->service)($this->endpoint, $this->method, $this->olderThanHours);

        return Command::SUCCESS;
    }

    protected function prepareArguments(InputInterface $input)
    {
        $this->endpoint = $input->getArgument('endpoint');

        $this->method = $input->getOption('method');
        Assert::oneOf($this->method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'all']);
        if ($this->method === 'all') {
            $this->method = null;
        }

        $this->olderThanHours = $input->getArgument('older_than_hours');

    }

    protected function configure(): void
    {
        $this
            ->addArgument('endpoint', InputArgument::REQUIRED, 'What endpoint do you want to hit?  (e.g. /api/admin/clients)')
            ->addArgument('older_than_hours', InputArgument::OPTIONAL, 'How many hours old logs do you want to delete?', 72)
            ->addOption('method',
                'm',
                InputArgument::IS_ARRAY,
                'What HTTP method do you want to use? ["GET", "POST", "PUT", "PATCH", "DELETE", "all"]',
                'all',
                function (CompletionInput $input): array {
                    $availableMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'all'];
                    $currentValue = $input->getCompletionValue();
                    return $availableMethods;
                })
        ;
    }
}

