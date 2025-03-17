<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Controller;

use Symfony\Component\HttpFoundation\Request;
use Wise\Core\Api\Dto\AbstractRequestDto;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\Api\Service\AbstractPresentationService;
use Wise\Core\ApiUi\Attributes\OpenApi\EndpointType\OADelete;
use Wise\Core\ApiUi\Controller\Core\AbstractUiApiController;
use Wise\Core\ApiUi\Dto\RequestDataDto\DeleteRequestDataDto;
use Wise\Core\Exception\CommonApiException\ParametersClassNotFoundException;
use Wise\Core\Exception\CommonApiException\ResponseClassNotFoundException;

/**
 * Kontroler bazowy dla endpointów DELETE
 */
abstract class AbstractDeleteController extends AbstractUiApiController
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

        $dto = new DeleteRequestDataDto();
        $dto->setParametersClass($this->getParametersClass());
        $dto->setParameters($request->query);
        $dto->setHeaders($request->headers);
        $dto->setAttributes($request->attributes->all());

        return $dto;
    }

    /**
     * Zwraca Alias zadeklarowany w atrybucie OpenApi
     * @return string|null
     * @throws ParametersClassNotFoundException
     * @throws \ReflectionException
     */
    protected function getParametersClass(): ?string
    {
        $parametersClass = $this->getParametersAlias();

        if($parametersClass === null){
            throw new ParametersClassNotFoundException('Klasa parameters nie została zadeklarowana. Czy na pewno korzystasz w kontrolerze z atrybutu OADelete?');
        }

        return $parametersClass;
    }

    /**
     * Zwraca Alias zadeklarowany w atrybucie OADelete
     * @return string|null
     * @throws \ReflectionException
     */
    protected function getParametersAlias(): ?string
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
}
