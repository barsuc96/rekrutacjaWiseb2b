<?php

declare(strict_types=1);

namespace Wise\Core\Api\Helper;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use Wise\Core\Api\Helper\Interfaces\OpenApiAliasResolverInterface;

/**
 * Klasa odpowiedzialna za wyszukiwania klas na podstawie aliasów zadeklarowanych w pliku nelmio.api_doc.yaml
 */
class OpenApiAliasResolver implements OpenApiAliasResolverInterface
{
    private $schemas = [];
    private $processedFiles = [];

    public function __construct(string $mainYamlFile)
    {
        $this->loadSchemasFromYamlFile($mainYamlFile);
    }

    /**
     * Wczytuje schematy z pliku YAML (zadeklarowanego w services.yaml)
     * Zadaniem metody jest pobrać wszystkie klasy zadeklarowane dla nelmio_api_doc
     * @param string $yamlFilePath
     * @param bool $isImportPath
     * @return void
     */
    private function loadSchemasFromYamlFile(string $yamlFilePath, bool $isImportPath = false): void
    {
        // Weryfikacja czy plik istnieje i czy nie został już wcześniej przetworzony
        if (!file_exists($yamlFilePath) || in_array($yamlFilePath, $this->processedFiles)) {
            return;
        }

        // Zapisanie pliku jako przetworzony
        $this->processedFiles[] = $yamlFilePath;

        // Parsowanie pliku YAML
        try {
            $yamlContent = file_get_contents($yamlFilePath);
            $parsedYaml = Yaml::parse($yamlContent);
        } catch (ParseException $e) {
            // Obsługa błędów parsowania YAML
            return;
        }

        // Weryfikacja czy plik zawiera importy
        if (isset($parsedYaml['imports'])) {
            foreach ($parsedYaml['imports'] as $import) {
                if (isset($import['resource'])) {
                    $importPath = dirname($yamlFilePath) . '/' . $import['resource'];
                    $this->loadSchemasFromYamlFile($importPath, true);
                }
            }
        }


        // Weryfikacja czy plik zawiera deklaracje klas
        if(isset($parsedYaml['nelmio_api_doc']['models']['names'])){
            foreach ($parsedYaml['nelmio_api_doc']['models']['names'] as $alias) {

                // Jako priorytet przyjmujemy aliasy z importów.
                // We wdrożeniu importujemy na początku vendor a później nasze przeciążenia
                // Zabezpieczenie, abyśmy zawsze mogli nadpisać aliasy z vendor
                if(!$isImportPath && isset($this->schemas[$alias['alias']])){
                    continue;
                }

                // Zapisanie aliasu w tablicy
                if(empty($alias['areas'])){
                    continue;
                }

                foreach ($alias['areas'] as $area){
                    $this->schemas[$area][$alias['alias']] = [
                        'className' => $alias['type'],
                        'areas' => $alias['areas']
                    ];
                }
            }
        }
    }

    /**
     * Weryfikuje czy dany alias istnieje w załadowanych schematach.
     * Jeśli tak to go zwraca, jeśli nie to zwraca null
     * @param string|null $alias
     * @return array|null
     */
    public function getInformationAboutClassByAlias(?string $alias, ?string $scope = null): ?array
    {
        if($alias === null){
            return null;
        }

        if (isset($this->schemas[$scope][$alias])) {
            return $this->schemas[$scope][$alias];
        }

        return null;
    }


    /**
     * Metoda statyczna dla atrybutów
     * Wczytuje schematy z pliku YAML (zadeklarowanego w services.yaml)
     * Zadaniem metody jest pobrać wszystkie klasy zadeklarowane dla nelmio_api_doc
     * @param string $yamlFilePath
     * @param array $schemas
     * @param bool $isImportPath
     * @return void
     */
    public static function loadSchemasFromNelmioYamlFile(string $yamlFilePath, array &$schemas, bool $isImportPath = false, ?string $scope = null): void
    {
        // Weryfikacja czy plik istnieje i czy nie został już wcześniej przetworzony
        if (!file_exists($yamlFilePath)) {
            return;
        }

        // Parsowanie pliku YAML
        try {
            $yamlContent = file_get_contents($yamlFilePath);
            $parsedYaml = Yaml::parse($yamlContent);
        } catch (ParseException $e) {
            // Obsługa błędów parsowania YAML
            return;
        }

        // Weryfikacja czy plik zawiera importy
        if (isset($parsedYaml['imports'])) {
            foreach ($parsedYaml['imports'] as $import) {
                if (isset($import['resource'])) {
                    $importPath = dirname($yamlFilePath) . '/' . $import['resource'];
                    self::loadSchemasFromNelmioYamlFile(yamlFilePath: $importPath, schemas: $schemas, isImportPath: true, scope: $scope);
                }
            }
        }


        // Weryfikacja czy plik zawiera deklaracje klas
        if(isset($parsedYaml['nelmio_api_doc']['models']['names'])){
            foreach ($parsedYaml['nelmio_api_doc']['models']['names'] as $alias) {

                // Jako priorytet przyjmujemy aliasy z importów.
                // We wdrożeniu importujemy na początku vendor a później nasze przeciążenia
                // Zabezpieczenie, abyśmy zawsze mogli nadpisać aliasy z vendor
                if(!$isImportPath && isset($schemas[$alias['alias']])){
                    continue;
                }

                // Weryfikacja czy alias jest zadeklarowany w odpowiednim area
                if($scope !== null && !in_array($scope, $alias['areas'])){
                    continue;
                }

                // Zapisanie aliasu w tablicy
                $schemas[$alias['alias']] = [
                    'className' => $alias['type'],
                    'areas' => $alias['areas']
                ];
            }


        }
    }

    /**
     * Metoda statyczna dla atrybutów
     * Weryfikuje czy dany alias istnieje w załadowanych schematach.
     * Jeśli tak to go zwraca, jeśli nie to zwraca null
     * @param string|null $yamlFile
     * @param string|null $alias
     * @return array|null
     */
    public static function getInformationAboutClassFromNelmioByAlias(string $yamlFile, ?string $alias, ?string $scope = null): ?array
    {
        $schemas = [];
        self::loadSchemasFromNelmioYamlFile(yamlFilePath: $yamlFile, schemas: $schemas, scope: $scope);

        if($alias === null){
            return null;
        }

        if (isset($schemas[$alias])) {
            return $schemas[$alias];
        }

        return null;
    }

}
