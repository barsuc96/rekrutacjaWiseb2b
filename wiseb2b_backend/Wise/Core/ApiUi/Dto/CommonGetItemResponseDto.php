<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Dto;

use JsonSerializable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Wise\Core\Dto\AbstractResponseDto;
use Wise\Core\Helper\Date\CommonDateTimeNormalizer;
use Wise\Core\Serializer\Normalizer\ArrayNormalizer;

/**
 * Klasa zawierający obiekt do zwrócenia w każdym zapytaniu GET object w ApiUi
 */
class CommonGetItemResponseDto implements JsonSerializable
{
    public function __construct(
        protected AbstractResponseDto|array|null $item = null
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function jsonSerialize(): JsonResponse
    {
        $nameConverter = new CamelCaseToSnakeCaseNameConverter();
        $objectNormalizer = new ObjectNormalizer(nameConverter: $nameConverter);
        $arrayNormalizer = new ArrayNormalizer($nameConverter, $objectNormalizer);
        $normalizer = new Serializer([new CommonDateTimeNormalizer(), $objectNormalizer, $arrayNormalizer]);

        $data = [];
        if (is_array($this->item)) {
            foreach ($this->item as $key => $item) {
                $key = $nameConverter->normalize($key);

                if (is_object($item) || is_array($item)) {
                    $data[$key] = $normalizer->normalize($item);
                } else {
                    $data[$key] = $item;
                }
            }
        } else {
            $data = $normalizer->normalize($this->item);
        }

        // Usuwam pola, które wygenerowały się niepotrzebnie
        unset($data['table_prefix']);
        unset($data['main_table_prefix']);


        return new JsonResponse($data);
    }

    public function getItem(): ?array
    {
        return $this->item;
    }

    public function setItem(?array $item): self
    {
        $this->item = $item;

        return $this;
    }
}
