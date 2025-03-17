<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Controller;

use Symfony\Component\HttpFoundation\Request;
use Wise\Core\Api\Dto\AbstractRequestDto;
use Wise\Core\Api\Helper\Interfaces\ControllerShareMethodsHelperInterface;
use Wise\Core\Api\Service\AbstractPresentationService;
use Wise\Core\ApiAdmin\Attributes\OpenApi\EndpointType\OAPatch;
use Wise\Core\ApiAdmin\Dto\RequestDataDto\PutRequestDataDto;

abstract class AbstractPatchAdminApiController extends AbstractPutAdminApiController
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
        /** @var PutRequestDataDto $dto */
        $dto = parent::prepareRequestDataDto($request);
        $dto->setIsPatch(true);

        return $dto;
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
        $attributes = $reflectionMethod->getAttributes(OAPatch::class);

        // Jeśli nie ma atrybutów zwracamy null
        if (empty($attributes)) {
            return null;
        }

        return $attributes[0]->newInstance()->getRequestDtoClass();
    }
}
