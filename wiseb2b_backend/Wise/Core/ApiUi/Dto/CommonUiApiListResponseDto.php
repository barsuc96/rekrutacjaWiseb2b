<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Dto;

use Doctrine\Common\Annotations\AnnotationReader;
use JsonSerializable;
use OpenApi\Attributes as OA;
use OpenApi\Generator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Wise\Core\DataTransformer\CommonDataTransformer;
use Wise\Core\Dto\AbstractResponseDto;
use Wise\Core\Helper\Date\CommonDateTimeNormalizer;
use Wise\Core\Serializer\Normalizer\ArrayNormalizer;

class CommonUiApiListResponseDto extends AbstractResponseDto implements JsonSerializable
{
    public function __construct(
        #[OA\Property(
            description: 'Strona',
            default: Generator::UNDEFINED,
            example: '1',
        )]
        protected int $page = 1,
        #[OA\Property(
            description: 'Ilość pobranych elementów',
            default: Generator::UNDEFINED,
            example: '10',
        )]
        protected int $totalCount = -1,
        #[OA\Property(
            description: 'Ilość stron',
            default: Generator::UNDEFINED,
            example: '2',
        )]
        protected int $totalPages = -1,
        protected ?array $items = []
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function jsonSerialize(): JsonResponse
    {
        $nameConverter = new CamelCaseToSnakeCaseNameConverter();
        $objectNormalizer = new ObjectNormalizer(
            new ClassMetadataFactory(
                new AnnotationLoader(
                    new AnnotationReader()
                )
            ), $nameConverter
        );
        $arrayNormalizer = new ArrayNormalizer($nameConverter, $objectNormalizer);
        $dateTimeNormalizer = new CommonDateTimeNormalizer(CommonDataTransformer::SERIALIZER_CONTEXT);
        $normalizer = new Serializer([$dateTimeNormalizer, $arrayNormalizer, $objectNormalizer]);

        $data = [
            'page' => $this->page,
            'total_count' => $this->totalCount,
            'total_pages' => $this->totalPages,
            'items' => []
        ];

        if (is_array($this->items)) {
            foreach ($this->items as $item) {
                $data['items'][] = $normalizer->normalize($item);
            }
        }

        return new JsonResponse($data);
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;
        return $this;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function setTotalCount(int $totalCount): self
    {
        $this->totalCount = $totalCount;
        return $this;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    public function setTotalPages(int $totalPages): self
    {
        $this->totalPages = $totalPages;
        return $this;
    }

    public function getItems(): ?array
    {
        return $this->items;
    }

    public function setItems(?array $items): self
    {
        $this->items = $items;
        return $this;
    }
}
