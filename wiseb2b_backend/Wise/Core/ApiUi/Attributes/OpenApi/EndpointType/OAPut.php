<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Attributes\OpenApi\EndpointType;

use OpenApi\Attributes as OpenApi;
use OpenApi\Attributes\ExternalDocumentation;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\RequestBody;
use Symfony\Component\HttpFoundation\Request;
use Wise\Core\Api\Attributes\trait\BuilderOpenApiHelperTrait;
use Wise\Core\Api\Helper\OpenApiAliasResolver;
use Wise\Core\ApiUi\Controller\Core\AbstractUiApiController;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Exception\DtoParseException;

/**
 * Atrybut OAPut służy do oznaczania metod lub klas jako operacji PUT w OpenAPI.
 *
 * Ta klasa rozszerza standardową adnotację OpenAPI `OpenApi\PUT`, dodając możliwości
 * powiązane z frameworkiem, jak i elastyczność w umieszczaniu jej na klasach lub metodach.
 * Atrybut jest konfigurowalny, umożliwiając wielokrotne użycie na jednej metodzie lub klasie
 * dzięki fladze `IS_REPEATABLE`.
 *
 * @see OpenApi\Put Dokumentacja OpenAPI dla operacji PUT.
 *
 * @Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class OAPut extends OpenApi\Put
{
    /**
     * Trait budujący komponenty OpenAPI na podstawie klasy DTO.
     */
    use BuilderOpenApiHelperTrait;

    const NELMIO_API_DOC_YAML_PATH = '/config/packages/nelmio_api_doc.yaml';
    private ?string $parametersDtoClass = null;

    /**
     * Konstruktor automatycznie tworzy komponenty OpenAPI na podstawie klasy DTO i jej schematu w plikach Nelmio.
     *
     * Konstruktor przyjmuje szereg parametrów związanych z dokumentacją OpenAPI, takich jak ścieżka, parametry,
     * body requestu, operacja, odpowiedzi, itd. Dodatkowo, jeśli przekazany jest obiekt JsonContent z referencją do
     * schematu, konstruktor próbuje automatycznie rozpoznać klasę DTO na podstawie aliasu w konfiguracji Nelmio.
     * Jeśli klasa DTO nie jest poprawnie rozpoznana lub nie jest instancją AbstractDto, zgłaszane są wyjątki.
     * Po zidentyfikowaniu klasy DTO, konstruktor generuje odpowiednie komponenty OpenAPI dla parametrów i request body.
     *
     * @param string|null $path Ścieżka operacji OpenAPI.
     * @param string|null $operationId Identyfikator operacji.
     * @param string|null $description Opis operacji.
     * @param string|null $summary Krótkie podsumowanie operacji.
     * @param array|null $security Zabezpieczenia związane z operacją.
     * @param array|null $servers Serwery związane z operacją.
     * @param RequestBody|null $requestBody Ciało żądania (request body).
     * @param array|null $tags Tagi przypisane do operacji.
     * @param array|null $parameters Parametry operacji.
     * @param array|null $responses Odpowiedzi operacji.
     * @param array|null $callbacks Wywołania zwrotne (callbacks) dla operacji.
     * @param ExternalDocumentation|null $externalDocs Zewnętrzna dokumentacja powiązana z operacją.
     * @param bool|null $deprecated Czy operacja jest przestarzała.
     * @param array|null $x Dodatkowe informacje (rozszerzenia OpenAPI).
     * @param array|null $attachables Dodatkowe obiekty możliwe do dołączenia.
     * @param string|null $parametersDtoClass Klasa DTO dla parametrów operacji.
     * @param JsonContent|null $requestDto Zawartość request body w formacie JSON.
     * @param string|null $scope Zakres działania operacji.
     *
     * @throws DtoParseException Jeśli klasa DTO nie zostanie poprawnie rozpoznana lub nie jest instancją AbstractDto.
     * @throws \ReflectionException
     */
    public function __construct(
        ?string $path = null,
        ?string $operationId = null,
        ?string $description = null,
        ?string $summary = null,
        ?array $security = null,
        ?array $servers = null,
        ?RequestBody $requestBody = null,
        ?array $tags = null,
        ?array $parameters = null,
        ?array $responses = null,
        ?array $callbacks = null,
        ?ExternalDocumentation $externalDocs = null,
        ?bool $deprecated = null,
        ?array $x = null,
        ?array $attachables = null,
        ?JsonContent $requestDto = null,
        ?string $scope = null
    ) {
        $requestDtoClass = null;

        // Jeśli przekazano obiekt JsonContent i zawiera on referencję do schematu, próbujemy rozpoznać klasę DTO
        if ($requestDto instanceof JsonContent && $requestDto->ref != null) {
            // Pobieramy alias schematu z referencji (usuwając prefiks '#/components/schemas/')
            $alias = str_replace('#/components/schemas/', '', $requestDto->ref);

            if (!empty($alias)) {
                // Ścieżka do pliku konfiguracyjnego Nelmio (zawierającego aliasy)
                $dir = $this->getProjectDir() . self::NELMIO_API_DOC_YAML_PATH;

                // Pobieramy informacje o klasie na podstawie aliasu z Nelmio
                $aliasInfo = OpenApiAliasResolver::getInformationAboutClassFromNelmioByAlias(
                    yamlFile: $dir,
                    alias: $alias,
                    scope: AbstractUiApiController::AREA_OPEN_API
                );

                // Jeśli znaleziono pełną nazwę klasy DTO, przypisujemy ją
                if (!empty($aliasInfo['className'])) {
                    $requestDtoClass = $aliasInfo['className'];
                }
            }
        }

        // Jeśli klasa DTO nie jest zdefiniowana, zgłaszamy wyjątek
        if ($requestDtoClass === null) {
            throw new DtoParseException(
                'Cannot automatically create parameters because parametersDtoClass is not set'
            );
        }

        // Tworzymy instancję klasy DTO
        $dto = new ($requestDtoClass)();
        // Sprawdzamy, czy utworzony obiekt jest instancją AbstractDto
        if (!($dto instanceof AbstractDto)) {
            // Jeśli nie, zgłaszamy wyjątek
            throw new DtoParseException(
                'Cannot automatically create parameters because ' . $requestDtoClass . ' is not an instance of ' . AbstractDto::class
            );
        }

        // Przypisujemy rozpoznaną klasę DTO do właściwości
        $this->parametersDtoClass = $requestDtoClass;

        // Generujemy komponenty OpenAPI na podstawie klasy DTO
        $openApiElements = $this->generateOpenApiComponents(
                                dtoClass: $requestDtoClass,
                                method: Request::METHOD_PUT,
                                scope: AbstractUiApiController::AREA_OPEN_API
                            );


        // Wywołujemy konstruktor klasy nadrzędnej, przekazując wygenerowane komponenty OpenAPI
        parent::__construct(
            $path,
            $operationId,
            $description,
            $summary,
            $security,
            $servers,
            $requestBody ?? $openApiElements['requestBody'],  // Przekazujemy wygenerowane request body
            $tags,
            $parameters ?? $openApiElements['parameters'],  // Przekazujemy wygenerowane parametry
            $responses ?? $openApiElements['responses'], // Przekazujemy wygenerowane odpowiedzi
            $callbacks,
            $externalDocs,
            $deprecated,
            $x,
            $attachables
        );
    }

    /**
     * Automatycznie wykrywa i zwraca katalog główny projektu na podstawie lokalizacji pliku kernelowego.
     *
     * Metoda wykorzystuje refleksję, aby znaleźć plik, w którym zdefiniowana jest bieżąca klasa.
     * Następnie iteracyjnie wstecz przeszukuje strukturę katalogów, aż znajdzie plik `composer.json`,
     * co oznacza, że znalazła katalog główny projektu. Jeśli nie uda się znaleźć pliku `composer.json`,
     * metoda zwróci katalog, w którym zlokalizowana jest klasa kernelowa.
     *
     * @return string Ścieżka do katalogu głównego projektu.
     * @throws \LogicException Jeśli nie uda się zlokalizować pliku dla klasy kernelowej.
     */
    protected function getProjectDir(): string
    {
        return '/var/www/backend';
    }

    /**
     * Zwraca pole `parametersDtoClass` klasy.
     * @return string|null
     */
    public function getParametersDtoClass(): ?string
    {
        return $this->parametersDtoClass;
    }

}

