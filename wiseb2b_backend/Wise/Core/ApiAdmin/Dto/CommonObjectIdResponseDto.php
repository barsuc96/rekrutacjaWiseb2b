<?php

declare(strict_types=1);


namespace Wise\Core\ApiAdmin\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Wise\Core\ApiAdmin\Enum\ResponseStatusEnum;
use Wise\Core\Dto\AbstractDto;

/**
 * Dto zwracany przez wszystkie metody zwracające ID obiektu, pomaga automatycznie przygotoować odpowiedź
 * @deprecated Zastąpione przez CommonAdminApiObjectResponseDto
 */
class CommonObjectIdResponseDto
{
    #[OA\Property(
        description: 'Status przetworzenia obiektu',
        example: '1',
    )]
    protected ?int $status = 1;

    #[OA\Property(
        description: 'Informacja o przetworzeniu obiektu',
        example: 'SUCCESS',
    )]
    protected ?string $message = 'SUCCESS';

    #[OA\Property(
        description: 'ID obiektu',
        example: 'ID-123',
    )]
    protected ?string $id;

    #[OA\Property(
        description: 'ID wewnętrzne obiektu',
        example: 1,
    )]
    protected ?int $internalId;

    #[OA\Property(
        description: 'Lista pól do poprawy',
        type: "array",
        items: new OA\Items(anyOf: [new OA\Schema(type: "string")]
        )
    )]
    protected ?array $fieldsInfo = null;

    public function __construct() {
        $this->status = ResponseStatusEnum::SUCCESS->value;
        $this->message = ResponseStatusEnum::SUCCESS->name;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getInternalId(): ?int
    {
        return $this->internalId;
    }

    public function setInternalId(?int $internalId): self
    {
        $this->internalId = $internalId;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
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

    public function prepareResponse(): array
    {
        $fieldsInfo = $this->getFieldsInfo() !== null ? $this->getFieldsInfo() : null;
        $this->setFieldsInfo(null);

        $normalizer = new ObjectNormalizer();
        $normalizedResponse = $normalizer->normalize($this);
        if(!empty($fieldsInfo)){
            $normalizedResponse['fieldsInfo'] = $fieldsInfo;
        }

        return $normalizedResponse;
    }

    public function prepareFromData(CommonObjectAdminApiResponseDto|AbstractDto $dto): void
    {
        if (method_exists($dto, 'getId') && $dto->isInitialized('id')) {
            $this->id = $dto->getId();
        }

        if (method_exists($dto, 'getInternalId') && $dto->isInitialized('internalId')) {
            $this->internalId = $dto->getInternalId();
        }
    }

    public function getFieldsInfo(): ?array
    {
        return $this->fieldsInfo;
    }

    public function setFieldsInfo(?array $fieldsInfo): self
    {
        $this->fieldsInfo = $fieldsInfo;

        return $this;
    }


}
