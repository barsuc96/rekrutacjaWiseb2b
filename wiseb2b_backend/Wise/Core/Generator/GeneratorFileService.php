<?php

namespace Wise\Core\Generator;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Wise\Core\Generator\Abstract\AbstractGeneratorFile;
use Wise\Core\Generator\Generators\EndpointFileGenerator\EndpointFileGenerator;
use Wise\Core\Generator\Generators\ServiceFileGenerator\ServiceFileGenerator;
use Wise\Core\Generator\Interfaces\GeneratorFileServiceInterface;

class GeneratorFileService implements GeneratorFileServiceInterface
{
    protected const GENERATORS = [
        EndpointFileGenerator::class,
        ServiceFileGenerator::class
    ];

    public function __construct(
        private readonly ContainerInterface $container
    ){}

    public function __invoke(InputInterface $input, OutputInterface $output, mixed $helper): void
    {
        $generators = $this->getGeneratorsToChoice();

        // Wybranie generatora
        $generator = $this->getGenerator($input, $output, $helper, $generators);

        // Generowanie plików
        $generator->generateFiles($input, $output, $helper);
    }

    /**
     * Metoda wyświetla zapytanie do użytkownika o wybranie generatora
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param mixed $helper
     * @param array $generators
     * @return AbstractGeneratorFile
     */
    protected function getGenerator(InputInterface $input, OutputInterface $output, mixed $helper, array $generators): AbstractGeneratorFile
    {
        $toChoiceGenerators = [];
        foreach ($generators as $generator) {
            $toChoiceGenerators[$generator->getName()] = $generator->getDescription();
        }

        $question = new ChoiceQuestion(
            'Co chcesz wygenerować? (domyślnie endpoint)',
            $toChoiceGenerators,
            0
        );
        $question->setErrorMessage('Generator %s niepoprawny.');

        $chosenGenerator = $helper->ask($input, $output, $question);
        $output->writeln('Wybrałeś: '. $chosenGenerator);

        if(!isset($generators[$chosenGenerator])){
            $output->writeln('Wybrany generator nie istnieje');
        }

        return $generators[$chosenGenerator];
    }


    /**
     * Zwraca dostępne generatory
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getGeneratorsToChoice(): array
    {
        $generators = [];
        foreach (self::GENERATORS as $generator) {
            /**
             * @var AbstractGeneratorFile $generator
             */
            $generator = $this->container->get($generator);
            $generators[$generator->getName()] = $generator;
        }

        return $generators;
    }
}
