<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Controller;

use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\Api\Dto\AbstractRequestDto;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\Api\Service\AbstractPresentationService;
use Wise\Core\ApiUi\Controller\Core\AbstractUiApiController;
use Wise\Core\ApiUi\Dto\RequestDataDto\PutRequestDataDto;
use Wise\Core\Exception\CommonApiException\RequestBodyClassNotFoundException;
use Wise\Core\Exception\CommonApiException\ResponseClassNotFoundException;

/**
 * Kontroler bazowy dla endpointów PUT
 */
abstract class AbstractPutController extends AbstractUiApiController
{
    public function __construct(
        private readonly ControllerShareMethodsHelperInterface $endpointShareMethodsHelper,
        private readonly AbstractPresentationService $abstractPresentationService
    ){
        parent::__construct($endpointShareMethodsHelper, $abstractPresentationService);
    }

    /**
     * Przygotowuje strukturę requesta, która zostanie przekazana do serwisu
     * @param Request $request
     * @return AbstractRequestDto
     * @throws ResponseClassNotFoundException
     * @throws \ReflectionException
     */
    protected function prepareRequestDataDto(Request $request): AbstractRequestDto
    {
        $dto = new PutRequestDataDto();
        $dto->setRequestDtoClass($this->getRequestBodyClass());
        $attributes = $this->prepareAttributes($dto, $request->attributes->all());

        if(empty($request->getContent())){
            $dto->setRequestContent(!empty($attributes) ? json_encode($attributes) : null);
        }else{
            $content = array_merge(json_decode($request->getContent(), true) ?? [], $attributes);
            $dto->setRequestContent(json_encode($content));
        }

        $dto->setParameters($this->getParameters($request));

        return $dto;
    }

    /**
     * Zwraca klasę requesta zadeklarowaną w atrybucie RequestBody OpenApi
     * @return string|null
     * @throws RequestBodyClassNotFoundException
     * @throws \ReflectionException
     */
    protected function getRequestBodyClass(): ?string
    {
        $requestBodyClass = $this->getRequestBodyAlias();
        if($requestBodyClass == null){
            return null;
        }

        return $requestBodyClass;
    }

    /**
     * Przygotowuje atrybuty, które zostaną przekazane do serwisu
     * @param string|null $parametersDtoClass
     * @param array $listOfAttributes
     * @return array
     * @throws \ReflectionException
     */
    protected function prepareAttributes(?PutRequestDataDto $requestDto, array $listOfAttributes): array
    {
        $attributes = [];

        if($requestDto->getRequestDtoClass() === null){
            return $attributes;
        }

        $reflectionClass = new ReflectionClass($requestDto->getRequestDtoClass());
        foreach ($reflectionClass->getProperties() as $property) {
            $propertyName = $property->getName();
            $propertyType = $property->getType();


            $attributesInProperty = $property->getAttributes(name: OA\Path::class);
            if($attributesInProperty === null){
                $attributesInProperty = $property->getAttributes(name: OA\Query::class);
            }

            // Pomijamy jeśli pole nie ma zadeklarowanego atrybutu związanego z parametrami zapytania
            if($attributesInProperty == null){
                continue;
            }


            // Weryfikuje czy w atrybutach znajduje się wartość dla danego pola
            if(array_key_exists($propertyName, $listOfAttributes)){

                // W związku, że atrybuty GET zwracane są w formie string zmieniamy je na odpowiedni typ
                $value = match ($propertyType->getName()){
                    'int' => intval($listOfAttributes[$propertyName]),
                    'float' => floatval($listOfAttributes[$propertyName]),
                    'bool' => boolval($listOfAttributes[$propertyName]),
                    'string' => strval($listOfAttributes[$propertyName]),
                    default => $listOfAttributes[$propertyName]
                };

                $attributes[$propertyName] = $value;
            }
        }

        return $attributes;
    }
}
