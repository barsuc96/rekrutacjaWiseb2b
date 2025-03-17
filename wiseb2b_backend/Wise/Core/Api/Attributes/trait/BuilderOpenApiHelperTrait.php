<?php

namespace Wise\Core\Api\Attributes\trait;

use OpenApi\Attributes as OpenApi;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Ignore;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement\Header;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement\Path;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement\Property;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement\Query;
use Wise\Core\ApiAdmin\Controller\AbstractAdminApiController;
use Wise\Core\ApiUi\Controller\Core\AbstractUiApiController;
use Wise\Core\ApiUi\Dto\CommonUiApiListResponseDto;

/**
 * Trait wspomagający generowanie komponentów OpenAPI dla klas DTO.
 */
trait BuilderOpenApiHelperTrait
{
    /**
     * Generuje komponenty OpenAPI dla danej klasy DTO, tworząc listę parametrów i body requestu.
     *
     * Metoda analizuje klasę DTO za pomocą refleksji, aby automatycznie wygenerować odpowiednie komponenty OpenAPI.
     * Jeśli właściwość klasy ma adnotację OpenApi\Parameter, jest dodawana do parametrów. Pozostałe właściwości
     * są traktowane jako elementy request body i odpowiednio generowane jako właściwości w schemacie OpenAPI.
     *
     * @param string $dtoClass Pełna kwalifikowana nazwa klasy DTO, dla której mają być wygenerowane komponenty OpenAPI.
     * @param string $scope Informacji o jakie API chodzi: 'admin', 'ui'
     * @return array Zwraca tablicę z wygenerowanymi parametrami i schematem request body.
     * @throws \ReflectionException
     */
    protected function generateOpenApiComponents(string $dtoClass, string $method, string $scope, array $parameters = []): array
    {
        $properties = [];  // Lista właściwości request body dla klasy DTO
        $responses = [];  // Lista odpowiedzi dla klasy DTO
        $requestBody = null;  // Request body dla klasy DTO

        // Tworzymy refleksję dla podanej klasy DTO
        $reflectionClass = new ReflectionClass($dtoClass);

        // Iterujemy po wszystkich właściwościach klasy DTO
        foreach ($reflectionClass->getProperties() as $property) {
            $propertyType = $property->getType();
            $typeName = $propertyType->getName();

            // Jeśli typ jest tablicą np. w GET response gdy zwracamy tablice obiektów w items przykład \Wise\Client\ApiUi\Dto\ClientsResponseDto
            if ($typeName === 'array') {
                $itemType = $this->getArrayItemType($property);
                if ($itemType) {
                    if (class_exists($itemType)) {
                        $propertiesClass = $this->generateOpenApiComponents($itemType, $method, $scope, $parameters);
                        if (!empty($propertiesClass['parameters'])) {
                            foreach ($propertiesClass['parameters'] as $parameter) {
                                $currentParameterName = $parameter->name;

                                // Weryfikacja czy parametr już istnieje
                                $isParameterExist = false;
                                foreach ($parameters as $parameterToFind) {
                                    if ($parameterToFind->name === $currentParameterName) {
                                        $isParameterExist = true;
                                        break;
                                    }
                                }

                                // Jeśli parametr nie istnieje to go dodaje do listy
                                if (!$isParameterExist) {
                                    $parameters[] = $parameter;
                                }
                            }
                        }
                    }
                }
            }


            // Pobieramy wszystkie atrybuty danej właściwości
            $propertyAttributes = $property->getAttributes();

            // Filtrujemy atrybuty, aby znaleźć OpenApi\Parameter
            $parameterAttribute = array_filter(
                $propertyAttributes,
                fn($attr
                ) => $attr->getName() === OpenApi\Parameter::class || $attr->getName() === Path::class || $attr->getName() === Query::class || $attr->getName() === Header::class
            );

            if (!empty($parameterAttribute)) {
                // Jeśli właściwość ma atrybut OpenApi\Parameter, kopiujemy go i przypisujemy dynamicznie nazwę pola
                $oaParameter = $parameterAttribute[0]->newInstance();
                $oaParameter->name = $property->getName(); // Dodajemy nazwę pola jako `name` dla OpenApi\Parameter
                $oaParameter->schema = new OpenApi\Schema(type: $this->getSchemaType($typeName)); // Ustawiamy typ danych dla parametru
                $parameters[] = $oaParameter;  // Dodajemy parametr do listy parametrów
            } else {
                $schemaProperty = $this->generatePropertySchema($property);
                if ($schemaProperty === null) {
                    continue;
                }

                $properties[] = $schemaProperty; // Generujemy schemat OpenApi\Property
            }
        }

        $this->setParameters($parameters, $method, $scope, $dtoClass);
        $this->setResponse($responses, $method, $properties, $scope);

        // Przygotowanie requestBody
        if (!empty($properties) && in_array($method, [Request::METHOD_PUT, Request::METHOD_POST, Request::METHOD_PATCH])) {
            $requestBody = new OpenApi\RequestBody(  // Request body z wygenerowanymi właściwościami
                required: true,  // Zaznaczamy, że body jest wymagane
                content: new OpenApi\JsonContent(  // Tworzymy content typu JSON
                    properties: $properties,  // Właściwości request body
                    type: 'object'  // Typ danych to obiekt
                )
            );
        }

        // Zwracamy wygenerowane komponenty OpenAPI: parametry oraz schemat request body
        return [
            'parameters' => $parameters,  // Lista parametrów
            'requestBody' => $requestBody, // Request body
            'responses' => $responses // Lista odpowiedzi
        ];
    }

    /**
     * Generuje schemat OpenAPI (OpenApi\Property) dla podanej właściwości klasy.
     *
     * Metoda wykorzystuje refleksję, aby odczytać informacje o właściwości i wygenerować odpowiedni
     * obiekt OpenApi\Property. Obsługuje zarówno proste typy danych (np. string, int), jak i złożone obiekty.
     * Jeśli właściwość jest tablicą, metoda identyfikuje typ elementów tablicy i odpowiednio generuje
     * schemat dla tych elementów. Dla zagnieżdżonych obiektów metoda rekursywnie generuje schematy dla
     * wszystkich właściwości wewnętrznych.
     *
     * @param ReflectionProperty $property Właściwość klasy, dla której ma zostać wygenerowany schemat.
     * @return Property Zwraca wygenerowany schemat OpenAPI dla danej właściwości.
     * @throws \ReflectionException
     */
    protected function generatePropertySchema(ReflectionProperty $property): ?OpenApi\Property
    {
        // Nazwa właściwości, która będzie przypisana do property w schemacie
        $propertyName = $property->getName();
        // Typ właściwości (np. string, int, obiekt) pobrany z refleksji
        $propertyType = $property->getType();
        // Pobieramy atrybuty OpenApi\Property z właściwości (jeśli istnieją)
        $propertyAttributes = $property->getAttributes(Property::class);
        if(empty($propertyAttributes)){
            $propertyAttributes = $property->getAttributes(OpenApi\Property::class);
        }


        // Sprawdzamy, czy posiada atrybut ignore bądź dotyczy parametrów endpointa, jeśli tak to go pomijamy
        $propertyIgnoreAttributes = $property->getAttributes(Ignore::class);

        // Jeśli klasa jest elementem tablicy w DTO, to dodatkowo weryfikuje czy nie dotyczy parametrów endpointa
        if(empty($propertyIgnoreAttributes)){
            $propertyIgnoreAttributes = $property->getAttributes(Query::class);
        }
        if(empty($propertyIgnoreAttributes)){
            $propertyIgnoreAttributes = $property->getAttributes(Path::class);
        }
        if(empty($propertyIgnoreAttributes)){
            $propertyIgnoreAttributes = $property->getAttributes(Header::class);
        }

        // Jeśli właściwość posiada nieodpowiedni atrybut, pomijamy generowanie schematu
        if (!empty($propertyIgnoreAttributes)) {
            return null;
        }



        // Obsługa pola

        $oaProperty = null;

        if (!empty($propertyAttributes)) {
            // Jeśli właściwość ma atrybut OpenApi\Property, używamy go
            $oaProperty = $propertyAttributes[0]->newInstance();
            // Upewniamy się, że atrybut ma ustawioną nazwę właściwości
            $oaProperty->property = $propertyName;
        } else {
            // Jeśli nie ma atrybutu, tworzymy nowy schemat OpenApi\Property z nazwą właściwości
            $oaProperty = new OpenApi\Property(property: $propertyName);
        }

        // Sprawdzamy typ właściwości (np. string, int, array, obiekt)
        if ($propertyType instanceof ReflectionNamedType) {
            $typeName = $propertyType->getName();

            if (class_exists($typeName)) {
                // Jeśli właściwość jest obiektem, generujemy schemat dla zagnieżdżonych właściwości
                $oaProperty->type = 'object';
                $nestedProperties = [];

                // Tworzymy refleksję dla zagnieżdżonej klasy (obiekt właściwości)
                $nestedClass = new ReflectionClass($typeName);
                // Iterujemy po każdej właściwości obiektu i generujemy dla niej schemat
                foreach ($nestedClass->getProperties() as $nestedProperty) {


                    $schemaProperty = $this->generatePropertySchema($nestedProperty);
                    if ($schemaProperty === null) {
                        continue;
                    }

                    $nestedProperties[] = $schemaProperty;
                }

                // Przypisujemy zagnieżdżone właściwości do schematu obiektu
                $oaProperty->properties = $nestedProperties;
            } elseif ($typeName === 'array') {
                // Jeśli właściwość jest tablicą, ustawiamy typ na 'array'
                $oaProperty->type = 'array';

                // Sprawdzamy typ elementów tablicy (np. obiekty, proste typy)
                $itemType = $this->getArrayItemType($property);
                if ($itemType) {
                    if (class_exists($itemType)) {
                        // Jeśli elementy tablicy są obiektami, generujemy dla nich schemat
                        $oaProperty->items = new OpenApi\Items(
                            properties: $this->generatePropertiesFromClass($itemType),
                            type: 'object'
                        );
                    } else {
                        // Jeśli elementy tablicy są typami prostymi (np. string, int)
                        $oaProperty->items = new OpenApi\Items(type: $itemType);
                    }
                }
            } else {
                // Jeśli właściwość jest prostym typem (np. string, int), ustawiamy typ
                $oaProperty->type = $typeName;
            }
        }

        // Zwracamy wygenerowany schemat OpenApi\Property
        return $oaProperty;
    }


    /**
     * Pobiera typ elementów tablicy zadeklarowanej w PHPDoc dla właściwości klasy.
     *
     * Funkcja analizuje komentarz PHPDoc przypisany do danej właściwości, szukając adnotacji `@var`,
     * która definiuje typ elementów tablicy (np. `@var ClassName[]`). Jeśli typ nie jest pełni
     * kwalifikowaną nazwą klasy (FQCN), funkcja próbuje odnaleźć przestrzeń nazw poprzez analizę
     * instrukcji `use` w klasie, do której należy właściwość. Funkcja obsługuje zarówno
     * częściowe, jak i w pełni kwalifikowane nazwy klas.
     *
     * @param ReflectionProperty $property Właściwość klasy, której typ elementów tablicy ma zostać określony.
     * @return string|null Zwraca pełni kwalifikowaną nazwę typu elementów tablicy lub null, jeśli nie można ustalić typu.
     */
    protected function getArrayItemType(ReflectionProperty $property): ?string
    {
        // Pobieramy komentarz PHPDoc dla właściwości
        $docComment = $property->getDocComment();
        // Pobieramy klasę, w której właściwość jest zadeklarowana
        $declaringClass = $property->getDeclaringClass();
        // Przestrzeń nazw klasy, w której znajduje się właściwość
        $namespace = $declaringClass->getNamespaceName();
        // Pobieramy instrukcje 'use' zadeklarowane w klasie, aby dopasować aliasy do pełnych nazw klas
        $useStatements = $this->getUseStatements($declaringClass);

        // Jeśli istnieje komentarz PHPDoc
        if ($docComment !== false) {
            // Dopasowujemy typ z adnotacji @var, np. @var ClassName[] $objects
            if (preg_match('/@var\s+([^\s]+)\s*\$/', $docComment, $matches)) {
                $varType = $matches[1];

                // Sprawdzamy, czy mamy do czynienia z tablicą (typ zakończony '[]')
                if (substr($varType, -2) === '[]') {
                    $itemType = substr($varType, 0, -2); // Usuwamy '[]', aby uzyskać typ elementu tablicy

                    // Jeśli itemType nie jest pełni kwalifikowaną nazwą klasy (nie zaczyna się od '\'),
                    // szukamy jego pełnej nazwy w instrukcjach 'use' w klasie
                    if ($itemType[0] !== '\\') {
                        // Próbujemy dopasować alias klasy z instrukcji 'use'
                        foreach ($useStatements as $alias => $className) {
                            if (str_contains($alias, $itemType)) {
                                return $className; // Znaleźliśmy pełną nazwę klasy
                            }
                        }

                        // Jeśli nie znaleziono w 'use', zakładamy, że typ jest w tej samej przestrzeni nazw co właściwość
                        return $namespace . '\\' . $itemType;
                    }

                    // Jeśli typ jest pełni kwalifikowaną nazwą (zaczyna się od '\'), zwracamy go bez zmian
                    return $itemType;
                }
            }

            // Druga próba dopasowania adnotacji @var bez dodatkowych informacji po typie, np. @var ClassName[]
            if (preg_match('/@var\s+([^\s]+)/', $docComment, $matches)) {
                $varType = $matches[1];

                // Sprawdzamy, czy mamy do czynienia z tablicą
                if (substr($varType, -2) === '[]') {
                    $itemType = substr($varType, 0, -2); // Usuwamy '[]', aby uzyskać typ elementu tablicy

                    // Jeśli itemType nie jest pełni kwalifikowaną nazwą klasy (nie zaczyna się od '\'),
                    // próbujemy znaleźć jego pełną nazwę
                    if ($itemType[0] !== '\\') {
                        // Ponownie próbujemy dopasować alias klasy z instrukcji 'use'
                        foreach ($useStatements as $alias => $className) {
                            if (str_contains($alias, $itemType)) {
                                return $className; // Znaleźliśmy pełną nazwę klasy
                            }
                        }

                        // Jeśli nie znaleziono aliasu, zakładamy, że typ znajduje się w tej samej przestrzeni nazw
                        return $namespace . '\\' . $itemType;
                    }

                    // Jeśli typ jest pełni kwalifikowaną nazwą, zwracamy go bez zmian
                    return $itemType;
                }
            }
        }

        // Jeśli nie udało się ustalić typu, zwracamy null
        return null;
    }

    /**
     * Generuje właściwości dla podanej klasy na podstawie jej pól.
     *
     * @param string $className Pełna kwalifikowana nazwa klasy, której właściwości mają zostać wygenerowane.
     * @return array Lista obiektów OpenApi\Property dla każdej właściwości klasy.
     * @throws \ReflectionException
     */
    protected function generatePropertiesFromClass(string $className): array
    {
        $properties = [];
        $reflectionClass = new ReflectionClass($className);

        // Iterujemy po wszystkich właściwościach klasy i generujemy dla nich odpowiednie schematy
        foreach ($reflectionClass->getProperties() as $property) {
            $schemaProperty = $this->generatePropertySchema($property);
            if ($schemaProperty === null) {
                continue;
            }

            $properties[] = $schemaProperty;
        }

        return $properties;
    }

    /**
     * Pobiera instrukcje 'use' z pliku źródłowego klasy, aby dopasować nazwy aliasów do pełnych nazw klas.
     *
     * @param ReflectionClass $class Obiekt klasy, dla której mają zostać pobrane instrukcje 'use'.
     * @return array Tablica instrukcji 'use', gdzie klucz to alias, a wartość to pełna kwalifikowana nazwa klasy.
     */
    protected function getUseStatements(ReflectionClass $class): array
    {
        $fileName = $class->getFileName();
        if (!$fileName || !file_exists($fileName)) {
            return [];
        }

        // Odczytujemy zawartość pliku klasy
        $fileContent = file_get_contents($fileName);
        $tokens = token_get_all($fileContent);
        $useStatements = [];
        $currentNamespace = '';
        $currentClass = '';

        // Iterujemy po tokenach, aby znaleźć przestrzeń nazw, klasę i instrukcje 'use'
        for ($i = 0, $count = count($tokens); $i < $count; $i++) {
            if ($tokens[$i][0] === T_NAMESPACE) {
                // Znaleziono przestrzeń nazw
                $currentNamespace = $this->parseNamespace($tokens, $i);
            } elseif ($tokens[$i][0] === T_CLASS && $currentNamespace) {
                // Znaleziono klasę
                $currentClass = $this->parseClass($tokens, $i);
            } elseif ($tokens[$i][0] === T_USE) {
                // Znaleziono instrukcję 'use'
                $useStatements = array_merge($useStatements, $this->parseUseStatements($tokens, $i));
            }
        }

        return $useStatements;
    }

    /**
     * Parsuje przestrzeń nazw z tokenów PHP.
     *
     * Metoda służy do odczytywania przestrzeni nazw z kodu PHP. Tokeny PHP to jednostki składniowe, które są
     * generowane przez funkcję `token_get_all()` i reprezentują elementy kodu, takie jak słowa kluczowe, zmienne
     * czy operatory. Funkcja ta odczytuje przestrzeń nazw z listy tokenów, co jest przydatne, gdy dynamicznie
     * analizujemy kod źródłowy i potrzebujemy informacji o pełnej kwalifikowanej nazwie klas, funkcji lub innych elementów.
     * @see https://www.php.net/manual/en/function.token-get-all.php Więcej informacji o funkcji `token_get_all()`.
     *
     * @param array $tokens Tokeny PHP pliku źródłowego uzyskane za pomocą funkcji token_get_all().
     * @param int $index Indeks bieżącego tokenu, od którego rozpoczynamy analizę.
     * @return string Zwraca nazwę przestrzeni nazw jako ciąg znaków.
     */
    protected function parseNamespace(array &$tokens, int &$index): string
    {
        $namespace = '';
        $count = count($tokens);

        // Parsujemy przestrzeń nazw aż do napotkania średnika, który kończy deklarację 'namespace'
        for ($index += 2; $index < $count; $index++) {
            if ($tokens[$index] === ';') {
                break;
            }
            // Sprawdzamy, czy dany token jest tablicą (np. zmienna, słowo kluczowe) czy pojedynczym znakiem
            $namespace .= is_array($tokens[$index]) ? $tokens[$index][1] : $tokens[$index];
        }

        // Zwracamy zebrany ciąg znaków jako przestrzeń nazw
        return trim($namespace);
    }

    /**
     * Parsuje nazwę klasy z tokenów PHP.
     *
     * Funkcja ta służy do odczytywania nazwy klasy z kodu PHP, reprezentowanej przez tokeny.
     * Użycie refleksji do pobierania informacji o klasach wymaga pełnej kwalifikowanej nazwy, którą odczytujemy za pomocą tej metody.
     *
     * @param array $tokens Tokeny PHP pliku źródłowego uzyskane za pomocą funkcji token_get_all().
     * @param int $index Indeks bieżącego tokenu, od którego zaczynamy parsowanie nazwy klasy.
     * @return string Zwraca nazwę klasy jako ciąg znaków.
     */
    protected function parseClass(array &$tokens, int &$index): string
    {
        $className = '';
        $count = count($tokens);

        // Parsujemy nazwę klasy aż do napotkania '{', który oznacza początek ciała klasy
        for ($index += 2; $index < $count; $index++) {
            if ($tokens[$index] === '{') {
                break;
            }
            // Sprawdzamy, czy dany token jest tablicą (np. zmienna, słowo kluczowe) czy pojedynczym znakiem
            $className .= is_array($tokens[$index]) ? $tokens[$index][1] : $tokens[$index];
        }

        // Zwracamy nazwę klasy
        return trim($className);
    }


    /**
     * Parsuje instrukcje 'use' z tokenów PHP.
     * Metoda ta odczytuje instrukcje 'use' z kodu PHP, które wskazują, jakie klasy lub przestrzenie nazw są importowane do pliku.
     *
     * @param array $tokens Tokeny PHP pliku źródłowego.
     * @param int $index Indeks bieżącego tokenu.
     * @return array Tablica instrukcji 'use', gdzie klucz to alias, a wartość to pełna kwalifikowana nazwa klasy.
     */
    protected function parseUseStatements(array &$tokens, int &$index): array
    {
        $useStatements = [];  // Tablica do przechowywania aliasów klas i ich pełnych nazw
        $count = count($tokens);

        $namespace = '';  // Zmienna do przechowywania pełnej nazwy klasy
        // Parsujemy instrukcje 'use' aż do napotkania średnika, który kończy deklarację
        for ($index += 2; $index < $count; $index++) {
            if ($tokens[$index] === ';') {
                // Dodajemy do tablicy mapowanie aliasu na pełną nazwę klasy
                $useStatements[basename($namespace)] = trim($namespace);
                break;
            }
            // Sprawdzamy, czy dany token jest tablicą (np. zmienna, słowo kluczowe) czy pojedynczym znakiem
            $namespace .= is_array($tokens[$index]) ? $tokens[$index][1] : $tokens[$index];
        }

        // Zwracamy tablicę instrukcji 'use'
        return $useStatements;
    }

    /**
     * Pobiera typ danych dla schematu OpenAPI.
     * @param string $typeName
     * @return string
     */
    protected function getSchemaType(string $typeName): string
    {
        $typeMapping = [
            'int' => 'integer',
            'float' => 'number',
            'bool' => 'boolean',
            'DateTimeInterface' => 'string',
        ];

        if (array_key_exists($typeName, $typeMapping)) {
            $typeName = $typeMapping[$typeName];
        }

        return $typeName;
    }

    /**
     * Ustawia odpowiedź zapytania
     * @param array $responses
     * @param string $method
     * @param array|null $properties
     * @return void
     */
    protected function setResponse(array &$responses, string $method, ?array $properties, string $scope): void
    {
        if($scope === AbstractAdminApiController::AREA_OPEN_API){
            $this->prepareResponseForAdminApi($responses, $method, $properties);
        }

        if($scope === AbstractUiApiController::AREA_OPEN_API){
            $this->prepareResponseForUiApi($responses, $method, $properties);
        }
    }




    // ==== PRZYGOTOWANIE RESPONSE ====

    /**
     * Przygotowuje odpowiedź dla ADMIN API
     * Elementy dodawane automatycznie
     * @param array $responses
     * @param string $method
     * @param array|null $properties
     * @return void
     */
    protected function prepareResponseForAdminApi(array &$responses, string $method, ?array $properties): void
    {

        if (in_array($method, [Request::METHOD_GET])) {
            if (!empty($properties)) {
                $responses[] = new OpenApi\Response(
                    response: Response::HTTP_OK,
                    description: "Poprawnie pobrano dane",
                    content: new OpenApi\JsonContent(
                        properties: $properties,
                        type: 'object'
                    )
                );
            }
        }

        if (in_array($method, [Request::METHOD_PUT, Request::METHOD_PATCH])) {
            $responses[] = new OpenApi\Response(
                response: Response::HTTP_OK,
                description: "Zwrotka w przypadku poprawnie przetworzonych wszystkich danych",
                content: new OpenApi\JsonContent(
                    ref: "#/components/schemas/CommonPutResponseAdminApiDto",
                    type: 'object'
                )
            );
        }

        $responses[] = new OpenApi\Response(
            response: Response::HTTP_UNAUTHORIZED,
            description: "Błędny token autoryzacyjny",
            content: new OpenApi\JsonContent(
                ref: "#/components/schemas/UnauthorizedResponseDto",
                type: "object"
            )
        );

        $responses[] = new OpenApi\Response(
            response: Response::HTTP_BAD_REQUEST,
            description: "Niepoprawne dane wejściowe",
            content: new OpenApi\JsonContent(
                ref: "#/components/schemas/InvalidInputDataResponseDto",
                type: "object"
            )
        );
    }

    /**
     * Przygotowuje odpowiedź dla UI API
     * Elementy dodawane automatycznie
     * @param array $responses
     * @param string $method
     * @param array|null $properties
     * @return void
     */
    protected function prepareResponseForUiApi(array &$responses, string $method, ?array $properties): void
    {
        if (in_array($method, [Request::METHOD_PUT, Request::METHOD_POST, Request::METHOD_DELETE])) {
            $responses[] = new OpenApi\Response(
                response: Response::HTTP_OK,
                description: "Poprawnie zapisano dane",
                content: new OpenApi\JsonContent(
                    ref: "#/components/schemas/Common200FormResponseDto",
                    type: "object"
                )
            );
            $responses[] = new OpenApi\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: "Wystąpiły błędy",
                content: new OpenApi\JsonContent(
                    ref: "#/components/schemas/Common422FormResponseDto",
                    type: "object"
                ),
            );
        }

        if (in_array($method, [Request::METHOD_GET])) {
            if (!empty($properties)) {
                $responses[] = new OpenApi\Response(
                    response: Response::HTTP_OK,
                    description: "Poprawnie pobrano dane",
                    content: new OpenApi\JsonContent(
                        properties: $properties,
                        type: 'object'
                    )
                );
            }

            $responses[] = new OpenApi\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: "Wystąpił problem podczas przetwarzania danych",
                content: new OpenApi\JsonContent(ref: "#/components/schemas/FailedResponseDto", type: "object"),
            );
        }
    }

    // ==== PRZYGOTOWANIE PARAMETRÓW ====


    /**
     * Ustawia parametry dla OpenApi
     * Elementy dodawane automatycznie
     * @param array $parameters
     * @param string $method
     * @param string|null $scope
     * @param string|null $dtoClass
     * @return void
     */
    protected function setParameters(array &$parameters, string $method, ?string $scope, ?string $dtoClass): void
    {
        if($scope === AbstractAdminApiController::AREA_OPEN_API){
            $this->prepareParametersForAdminApi($parameters, $method, $scope);
        }

        if($scope === AbstractUiApiController::AREA_OPEN_API){
            $this->prepareParametersForUiApi($parameters, $method, $scope, $dtoClass);
        }
    }

    /**
     * Przygotowuje parametrów dla ADMIN API
     * Elementy dodawane automatycznie
     * @param array $parameters
     * @param string $method
     * @param string|null $scope
     * @return void
     */
    protected function prepareParametersForAdminApi(array &$parameters, string $method, ?string $scope): void
    {
        $this->setRequestUUID($parameters);
    }

    /**
     * Przygotowuje parametrów dla UI API
     * Elementy dodawane automatycznie
     * @param array $parameters
     * @param string $method
     * @param string|null $scope
     * @return void
     */
    protected function prepareParametersForUiApi(array &$parameters, string $method, ?string $scope, ?string $dtoClass): void
    {
        $this->setPageAndLimitParameterOnListEndpoint($parameters, $dtoClass);

        $this->setContentLanguageParameter($parameters);
    }

    // ==== DODATKOWE METODY ====

    /**
     * Ustawia parametr contentLanguage w parametrach OpenApi.
     * @param array $parameters
     * @return void
     */
    protected function setContentLanguageParameter(array &$parameters): void
    {
        // Weryfikacja czy parametr ContentLanguage już istnieje
        $isParameterContentLanguageExist = false;
        foreach ($parameters as $parameterToFind) {
            if ($parameterToFind->name === 'contentLanguage') {
                $isParameterContentLanguageExist = true;
                break;
            }
        }

        // Jeśli parametr nie istnieje to go dodaje do listy
        if (!$isParameterContentLanguageExist) {
            $parameters[] = new OpenApi\Parameter(
                name: 'contentLanguage',
                description: 'Wersja językowa',
                in: 'header',
                required: true,
                schema: new OpenApi\Schema(
                    type: 'string',
                    example: 'pl'
                )
            );
        }
    }


    /**
     * Ustawia page i limit w endpointach typu list
     * @param array $parameters
     * @param string|null $dtoClass
     * @return void
     */
    protected function setPageAndLimitParameterOnListEndpoint(array &$parameters, ?string $dtoClass): void
    {
        if(!is_subclass_of($dtoClass, CommonUiApiListResponseDto::class)){
            return;
        }

        // Weryfikacja czy parametr ContentLanguage już istnieje
        $isParameterPageExist = false;
        $isParameterLimitExist = false;
        foreach ($parameters as $parameterToFind) {
            if ($parameterToFind->name === 'page') {
                $isParameterPageExist = true;
                continue;
            }

            if ($parameterToFind->name === 'limit') {
                $isParameterLimitExist = true;
            }
        }


        // Jeśli parametr nie istnieje to go dodaje do listy
        if (!$isParameterPageExist) {
            $parameters[] = new OpenApi\Parameter(
                name: 'page',
                description: 'Strona',
                in: 'query',
                required: false,
                schema: new OpenApi\Schema(
                    type: 'integer',
                    example: 1
                )
            );
        }

        // Jeśli parametr nie istnieje to go dodaje do listy
        if (!$isParameterLimitExist) {
            $parameters[] = new OpenApi\Parameter(
                name: 'limit',
                description: 'Ilość na stronie',
                in: 'query',
                required: false,
                schema: new OpenApi\Schema(
                    type: 'integer',
                    example: 10
                )
            );
        }



    }

    /**
     * Ustawia parametr requestUUID w parametrach OpenApi.
     * @param array $parameters
     * @return void
     */
    protected function setRequestUUID(array &$parameters): void
    {
        // Weryfikacja czy parametr x-request-uuid już istnieje
        $isParameterRequestUUIDExist = false;
        foreach ($parameters as $parameterToFind) {
            if ($parameterToFind->name === 'x-request-uuid') {
                $isParameterRequestUUIDExist = true;
                break;
            }
        }

        // Jeśli parametr nie istnieje to go dodaje do listy
        if (!$isParameterRequestUUIDExist) {
            $parameters[] = new OpenApi\Parameter(
                name: 'x-request-uuid',
                description: 'UUID requestu',
                in: 'header',
                required: true,
                schema: new OpenApi\Schema(
                    type: 'string',
                    example: '49c9aa13-c5c3-474b-a874-755f9d553779'
                )
            );
        }
    }
}
