<?php

declare(strict_types=1);

namespace Wise\Core\Api\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Wise\Core\Api\Dto\AbstractRequestDto;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\Api\Service\AbstractPresentationService;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPost;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAPut;

/**
 * Klasa bazowa obsługująca zachowanie wszystkich kontrolerów
 * Zawiera bazowy proces oraz elementy wspomagające
 */
abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly AbstractPresentationService $abstractPresentationService
    ){}

    /**
     * Obsługa endpointu
     * @param Request $request
     * @return JsonResponse
     */
    public function getAction(Request $request): JsonResponse
    {
        $requestDataDto = $this->prepareRequestDataDto($request);

        return $this->abstractPresentationService->process($requestDataDto);
    }

    /**
     * Zwraca Alias zadeklarowany w atrybucie RequestBody OpenApi
     * @return string|null
     * @throws \ReflectionException
     */
    protected function getRequestBodyAlias(): ?string
    {
        // Pobieramy nazwę bazowego kontrolera
        $calledClass = get_called_class();
        $method = 'getAction';

        // Za pomocą refleksji pobieramy atrybuty metody
        $reflectionMethod = new \ReflectionMethod($calledClass, $method);
        $attributes = $reflectionMethod->getAttributes(OAPost::class);

        if(empty($attributes)){
            $attributes = $reflectionMethod->getAttributes(OAPut::class);
        }

        // Jeśli nie ma atrybutów zwracamy null
        if (empty($attributes)) {
            return null;
        }

        // Pobieramy pierwszy atrybut (założenie, że na metodę przypada tylko jeden atrybut OARequestBody)
        $attributeInstance = $attributes[0]->newInstance();

        // Wyciągamy alias z instancji atrybutu
        try{
            $ref = $attributeInstance->getParametersDtoClass();
        }catch (\Exception $e){
            return null;
        }

        // Usuwamy zbędne fragmenty z aliasu i go zwracamy
        return str_replace("#/components/schemas/", "", $ref);
    }

    /**
     * Zwraca Alias zadeklarowany w atrybucie Response OpenApi
     * @return string|null
     * @throws \ReflectionException
     */
    protected function getResponseAlias(): ?string
    {
        // Pobieramy nazwę bazowego kontrolera
        $calledClass = get_called_class();
        $method = 'getAction';

        // Za pomocą refleksji pobieramy atrybuty metody
        $reflectionMethod = new \ReflectionMethod($calledClass, $method);
        $attributes = $reflectionMethod->getAttributes(OAGet::class);

        // Jeśli nie ma atrybutów zwracamy null
        if (empty($attributes)) {
            return null;
        }

        return $attributes[0]->newInstance()->getResponseDtoClass();
    }

    /**
     * Przygotowuje strukturę requesta, która zostanie przekazana do serwisu
     * @param Request $request
     * @return AbstractRequestDto
     */
    abstract protected function prepareRequestDataDto(Request $request): AbstractRequestDto;
}
