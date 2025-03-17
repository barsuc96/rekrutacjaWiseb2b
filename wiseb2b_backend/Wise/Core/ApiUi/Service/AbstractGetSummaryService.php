<?php

declare(strict_types=1);


namespace Wise\Core\ApiUi\Service;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Wise\Core\ApiUi\Dto\CommonGetItemResponseDto;
use Wise\Core\ApiUi\Dto\CommonGetUiApiDto;
use Wise\Core\ApiUi\ServiceInterface\ApiUiGetServiceInterface;
use Wise\Core\Exception\ObjectValidationException;
use Wise\Core\Helper\String\StringHelper;
use Wise\Core\Validator\ObjectValidator;

/**
 * @deprecated - Wykorzystaj \Wise\Core\ApiUi\Service\AbstractGetDetailsUiApiService
 */
abstract class AbstractGetSummaryService implements ApiUiGetServiceInterface
{
    public function __construct(
        protected readonly SerializerInterface $serializer,
        protected readonly ObjectValidator $objectValidator
    ) {
    }

    /**
     * TODO: W przyszłości możemy zrefaktorować controlery i przekazywać do serwisów dto:
     *  https://gist.github.com/cierzniak/5ce0449980d0212747dab3d4b134326a
     *
     * @param Request $request
     * @param string $dtoClass
     * @param array|null $attributes
     * @return JsonResponse
     * @throws ExceptionInterface
     * @throws ObjectValidationException
     */
    final public function process(Request $request, string $dtoClass, ?array $attributes = []): JsonResponse
    {
        $parameters = $request->query;

        $normalizer = new ObjectNormalizer();

        /** @var CommonGetUiApiDto $dto */
        $dto = $normalizer->denormalize($parameters->all(), $dtoClass);
        $this->objectValidator->validate($dto);

        // Przerobienie parametrów z snake_case na camelCase
        $parametersAdjusted = new InputBag();
        foreach ($parameters->all() as $key => $parameterValue) {
            $parametersAdjusted->add([StringHelper::snakeToCamel($key) => $parameterValue]);
        }

        $objects = $this->get($parametersAdjusted);

        return (new CommonGetItemResponseDto(
            $objects
        ))->jsonSerialize();
    }
}
