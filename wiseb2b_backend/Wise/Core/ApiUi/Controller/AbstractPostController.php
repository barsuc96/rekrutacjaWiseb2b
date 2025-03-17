<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Controller;

use Symfony\Component\HttpFoundation\Request;
use Wise\Core\Api\Dto\AbstractRequestDto;
use Wise\Core\ApiUi\Dto\RequestDataDto\PostRequestDataDto;
use Wise\Core\Exception\CommonApiException\ResponseClassNotFoundException;

/**
 * Kontroler bazowy dla endpointów POST
 */
abstract class AbstractPostController extends AbstractPutController
{
    /**
     * Przygotowuje strukturę requesta, która zostanie przekazana do serwisu
     * @param Request $request
     * @return AbstractRequestDto
     * @throws ResponseClassNotFoundException
     * @throws \ReflectionException
     */
    protected function prepareRequestDataDto(Request $request): AbstractRequestDto
    {
        $dto = new PostRequestDataDto();
        $dto->setRequestDtoClass($this->getRequestBodyClass());
        $attributes = $this->prepareAttributes($dto, $request->attributes->all());

        if (empty($request->getContent())) {
            $dto->setRequestContent(!empty($attributes) ? json_encode($attributes) : null);
        } else {
            $content = array_merge(json_decode($request->getContent(), true) ?? [], $attributes);
            $dto->setRequestContent(json_encode($content));
        }

        $dto->setParameters($this->getParameters($request));

        return $dto;
    }
}
