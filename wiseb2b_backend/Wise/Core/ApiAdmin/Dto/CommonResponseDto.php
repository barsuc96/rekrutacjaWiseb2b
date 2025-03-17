<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Dto;

use Codeception\Constraint\Page;
use JsonSerializable;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Wise\Core\ApiAdmin\Enum\ResponseStatusEnum;
use Wise\Core\Dto\AbstractResponseDto;
use Wise\Core\Serializer\Normalizer\ArrayNormalizer;

/**
 * Dto zwracany przez wszystkie metody zwracające obiekty, pomaga automatycznie przygotoować odpowiedź
 */
class CommonResponseDto extends AbstractResponseDto implements JsonSerializable
{
    public function __construct(
        #[OA\Property(
            description: 'Status zwrotny',
        )]
        protected ResponseStatusEnum $status,

        #[OA\Property(
            description: 'Wiadomość zwrotna',
            example: 'SUCCESS'
        )]
        protected ?string $message = null,

        /** @var CommonObjectIdResponseDto[] $objects */
        protected ?array $objects = [],

        #[OA\Property(
            description: 'Ilość zwróconych obiektów',
            example: 1
        )]
        protected ?int $count = null,

        #[OA\Property(
            description: 'Data ostatniej modyfikacji',
            example: '2023-01-01 00:00:01'
        )]
        protected ?string $lastChangeDate = null,

        /** @var ?CommonGetAdminApiDto $inputParameters */
        protected ?CommonGetAdminApiDto $inputParameters = null,

        /** @var string[] $headers */
        #[Ignore]
        protected ?array $headers = null
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
        $normalizer = new Serializer([$objectNormalizer, $arrayNormalizer]);

        $data = [
            "status" => $this->status->value
        ];

        if (is_null($this->message)) {
            $data['message'] = $this->status->name;
        } else {
            $data['message'] = $this->message;
        }

        if (!is_null($this->count)) {
            $data['count'] = $this->count;
        }

        if (!is_null($this->lastChangeDate)) {
            $data['last_change_date'] = $this->lastChangeDate;
        }

        if (!is_null($this->inputParameters)) {
            $data['input_parameters'] = $normalizer->normalize($this->inputParameters);
        }

        if (!is_null($this->objects) && count($this->objects) > 0) {
            foreach ($this->objects as $object) {
                // Normalizuje obiekt
                $normalizedObject = $normalizer->normalize($object);

                // Upewniam się, że zwracam pojedyńczy obiekt, a nie tablicę z jednym obiektem
                if(is_array($normalizedObject) && count($normalizedObject) == 1 && is_array($normalizedObject[0])){
                    $normalizedObject = $normalizedObject[0];
                }

                $data['objects'][] = $normalizedObject;
            }
        }

        return new JsonResponse(data: $data, headers: $this->headers ?? []);
    }

    public function getStatus(): ResponseStatusEnum
    {
        return $this->status;
    }

    public function setStatus(ResponseStatusEnum $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getObjects(): ?array
    {
        return $this->objects;
    }

    public function setObjects(?array $objects): self
    {
        $this->objects = $objects;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(?int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getLastChangeDate(): ?string
    {
        return $this->lastChangeDate;
    }

    public function setLastChangeDate(?string $lastChangeDate): self
    {
        $this->lastChangeDate = $lastChangeDate;

        return $this;
    }

    public function getInputParameters(): ?CommonGetAdminApiDto
    {
        return $this->inputParameters;
    }

    public function setInputParameters(?CommonGetAdminApiDto $inputParameters): self
    {
        $this->inputParameters = $inputParameters;

        return $this;
    }

    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    public function setHeaders(?array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }
}
