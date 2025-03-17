<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Controller;

use Symfony\Component\HttpFoundation\Request;
use Wise\Core\Api\Dto\AbstractRequestDto;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\Api\Service\AbstractPresentationService;
use Wise\Core\ApiAdmin\Attributes\OpenApi\EndpointType\OADelete;
use Wise\Core\ApiAdmin\Dto\RequestDataDto\DeleteSingleObjectAdminApiRequestDataDto;
use Wise\Core\Exception\CommonApiException\ParametersClassNotFoundException;

abstract class AbstractDeleteAdminApiController extends AbstractAdminApiController
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
        foreach ($request->attributes->get('_route_params') as $key => $value) {
            $request->query->add([$key => $value]);
        }

        $dto = new DeleteSingleObjectAdminApiRequestDataDto();
        $dto->setParameters($request->query);
        $dto->setHeaders($request->headers);
        $dto->setParametersDtoClass($this->getParametersClass());

        return $dto;
    }

    /**
     * Zwraca Alias zadeklarowany w atrybucie OpenApi
     * @return string|null
     * @throws \ReflectionException
     */
    protected function getParametersClass(): ?string
    {
        // Pobieramy nazwę bazowego kontrolera
        $calledClass = get_called_class();
        $method = 'getAction';

        // Za pomocą refleksji pobieramy atrybuty metody
        $reflectionMethod = new \ReflectionMethod($calledClass, $method);
        $attributes = $reflectionMethod->getAttributes(OADelete::class);

        // Jeśli nie ma atrybutów zwracamy null
        if (empty($attributes)) {
            return null;
        }

        return $attributes[0]->newInstance()->getParametersDtoClass();
    }

    /**
     * Zwraca ścieżkę do klasy zadeklarowanej w atrybucie Response OpenApi
     * @return string|null
     * @throws ParametersClassNotFoundException
     * @throws \ReflectionException
     */
    protected function getResponseClass(): ?string
    {
        $responseClass = $this->getResponseAlias();

        if($responseClass === null){
            throw new ParametersClassNotFoundException('Klasa parametrów nie została zadeklarowana. Czy na pewno korzystasz w kontrolerze z atrybutu OADelete?');
        }

        return $responseClass;
    }

}
