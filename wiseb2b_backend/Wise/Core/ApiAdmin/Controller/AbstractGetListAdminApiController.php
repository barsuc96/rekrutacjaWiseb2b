<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Controller;

use Symfony\Component\HttpFoundation\Request;
use Wise\Core\Api\Dto\AbstractRequestDto;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\Api\Service\AbstractPresentationService;
use Wise\Core\ApiAdmin\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiAdmin\Dto\RequestDataDto\GetSingleObjectAdminApiRequestDataDto;
use Wise\Core\Exception\CommonApiException\ResponseClassNotFoundException;

abstract class AbstractGetListAdminApiController extends AbstractAdminApiController
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
        $dto = new GetSingleObjectAdminApiRequestDataDto();
        $dto->setParameters($request->query);
        $dto->setHeaders($request->headers);
        $dto->setResponseDtoClass($this->getResponseClass());

        return $dto;
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
     * Zwraca ścieżkę do klasy zadeklarowanej w atrybucie Response OpenApi
     * @return string|null
     * @throws ResponseClassNotFoundException
     * @throws \ReflectionException
     */
    protected function getResponseClass(): ?string
    {
        $responseClass = $this->getResponseAlias();

        if($responseClass === null){
            throw new ResponseClassNotFoundException('Klasa response nie została zadeklarowana. Czy na pewno korzystasz w kontrolerze z atrybutu OAGet?');
        }

        return $responseClass;
    }
}
