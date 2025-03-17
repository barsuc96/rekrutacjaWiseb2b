<?php

namespace Wise\Core\Generator\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Wise\Core\Generator\Interfaces\GeneratorFileServiceInterface;

#[AsCommand(
    name: 'wise:generate',
    description: 'Komenda do automatycznego tworzenia plików',
    hidden: false
)]
class GenerateFilesCommand extends Command
{
    public function __construct(
        private readonly GeneratorFileServiceInterface $generatorFileService
    ){
        parent::__construct();
    }
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        ($this->generatorFileService)($input, $output, $helper);

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('Komenda do automatycznego tworzenia plików');
    }
}
