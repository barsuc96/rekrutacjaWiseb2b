<?php

namespace Wise\Core\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\KernelInterface;
use Wise\Core\Exception\InvalidInputDataException;
use Wise\Core\Exception\ObjectExistsException;

abstract class AbstractJsonRepository extends AbstractArrayRepository
{
    protected const ENTITY_CLASS = '';

    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly string $pathToFile,
        ManagerRegistry $registry,
        ?string $entity = null
    ){
        parent::__construct($registry, $entity);
    }

    protected function getJsonFileData(): array
    {
        $jsonFile = $this->getJsonFilePath();
        if ($jsonFile === null) {
            throw new ObjectExistsException('Plik JSON nie istnieje');
        }

        $jsonData = file_get_contents($jsonFile);
        if ($jsonData === false) {
            throw new InvalidInputDataException('Nie można otworzyć pliku JSON');
        }

        $data = json_decode($jsonData, true);
        if ($data === null) {
            throw new InvalidInputDataException('Błąd podczas przekształcania JSON na tablicę');
        }

        return $data;
    }

    protected function getJsonFilePath(): ?string
    {
        // Sprawdzam czy istnieje w obecnym projekcie plik JSON
        $currentPath = $this->kernel->getProjectDir() . '/' . $this->pathToFile;
        if(file_exists($currentPath)){
            return $currentPath;
        }

        // Sprawdzam w vendorze, czy istnieje plik JSON (aby wdrożenie mogło czytać JSONY ze standardu)
        $currentPath = $this->kernel->getProjectDir() . '/vendor/wiseb2b-git/wiseb2b_20_backend/' . $this->pathToFile;
        if(file_exists($currentPath)){
            return $currentPath;
        }

        return null;
    }
}
