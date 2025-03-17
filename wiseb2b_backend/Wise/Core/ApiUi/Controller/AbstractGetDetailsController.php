<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Controller;

use Symfony\Component\HttpFoundation\Request;
use Wise\Core\Api\Dto\AbstractRequestDto;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\Api\Service\AbstractPresentationService;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OAGet;
use Wise\Core\ApiUi\Dto\RequestDataDto\GetDetailsRequestDataDto;
use Wise\Core\Exception\CommonApiException\ResponseClassNotFoundException;

/**
 * Kontroler bazowy dla endpointów GET DETAILS
 */
abstract class AbstractGetDetailsController extends AbstractGetListController
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
        foreach ($request->attributes->get('_route_params') as $key => $value) {
            $request->query->add([$key => $value]);
        }

        $dto = new GetDetailsRequestDataDto();
        $dto->setParameters($request->query);
        $dto->setHeaders($request->headers);
        $dto->setResponseDtoClass($this->getResponseClass());
        $dto->setAttributes($request->attributes->all());

        return $dto;
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
}
