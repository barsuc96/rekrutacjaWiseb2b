<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Controller;

use Symfony\Component\HttpFoundation\Request;
use Wise\Core\Api\Dto\AbstractRequestDto;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\Api\Service\AbstractPresentationService;
use Wise\Core\ApiAdmin\Attributes\OpenApi\EndpointType\OAPut;
use Wise\Core\ApiAdmin\Dto\RequestDataDto\PutRequestDataDto;
use Wise\Core\Exception\CommonApiException\RequestBodyClassNotFoundException;
use Wise\Core\Exception\CommonApiException\ResponseClassNotFoundException;

abstract class AbstractPutAdminApiController extends AbstractAdminApiController
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
     */
    protected function prepareRequestDataDto(Request $request): AbstractRequestDto
    {
        $content = array_merge(json_decode($request->getContent(), true) ?? [], $request->attributes->all());

        $dto = new PutRequestDataDto();
        $dto->setRequestContent(json_encode($content));
        $dto->setHeaders($request->headers);
        $dto->setRequestDtoClass($this->getRequestDtoClass());
        $dto->setClearRequestContent($request->getContent());

        return $dto;
    }

    /**
     * Zwraca ścieżkę do klasy zadeklarowanej w atrybucie RequestBody OpenApi
     * @return string|null
     * @throws ResponseClassNotFoundException
     * @throws \ReflectionException
     */
    protected function getRequestDtoClass(): ?string
    {
        $requestBodyClass = $this->getRequestBodyAlias();

        if($requestBodyClass === null){
            throw new RequestBodyClassNotFoundException();
        }

        return $requestBodyClass;
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
        $attributes = $reflectionMethod->getAttributes(OAPut::class);

        // Jeśli nie ma atrybutów zwracamy null
        if (empty($attributes)) {
            return null;
        }

        return $attributes[0]->newInstance()->getRequestDtoClass();
    }
}
