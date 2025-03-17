<?php

namespace Wise\Core\Generator\Abstract;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Klasa abstrakcyjna generatora plików
 */
abstract class AbstractGeneratorFile
{
    /**
     * Nazwa generatora
     * @var string
     */
    protected string $name = '';

    /**
     * Opis generatora
     * @var string
     */
    protected string $description = '';


    /**
     * Zwraca nazwę generatora
     * @return string
     */
    public function getName(): string
    {
        return str_replace([' ', '-', '/', '\\'], '_', strtoupper($this->name));
    }

    /**
     * Zwraca opis generatora
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Metoda generująca pliki
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param mixed $helper
     * @return void
     */
    abstract function generateFiles(InputInterface $input, OutputInterface $output, mixed $helper): void;
}
