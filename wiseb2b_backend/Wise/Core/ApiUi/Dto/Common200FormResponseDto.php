<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Wise\Core\Helper\Date\CommonDateTimeNormalizer;
use Wise\Core\Serializer\Normalizer\ArrayNormalizer;

/**
 * TODO: [ws] dodać opis, nie wiem jak to działa
 */
class Common200FormResponseDto extends CommonFormResponseDto
{
    // TODO: Do ogarnięcia dlaczego nie mogę dać w adnotacji string[]|object, próbowałem oznaczyć
    //  za pomocą #[OA\Property(..., oneOf: ...), ale też nie zadziałało.
    #[OA\Property(description: 'Dane zwrócone przez request')]
    /** @var string[] $data */
    protected array|object|null $data = [];

    /** @var list<FieldInfoDto> $fieldsInfo */
    #[OA\Property(description: 'Lista pól do poprawy')]
    protected array $fieldsInfo = [];

    #[OA\Property(description: 'Czy wyświetlić wiadomość fieldInfos', example: false)]
    protected bool $showFieldInfos = true;

    /** @return string[]|object */
    public function getData(): array|object|null
    {
        return $this->data;
    }

    /** @param string[]|object $data */
    public function setData(array|object|null $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function isShowFieldInfos(): bool
    {
        return $this->showFieldInfos;
    }
    public function setShowFieldInfos(bool $showFieldInfos): self
    {
        $this->showFieldInfos = $showFieldInfos;

        return $this;
    }

    /**
     * @throws ExceptionInterface
     */
    public function jsonSerialize(): JsonResponse
    {
        $data = [
            'status' => $this->status,
            'show_modal' => $this->showModal,
            'message' => $this->message,
            'message_style' => $this->messageStyle,
            'show_message' => $this->showMessage,
            'show_field_infos' => $this->showFieldInfos,
        ];

        $nameConverter = new CamelCaseToSnakeCaseNameConverter();
        $objectNormalizer = new ObjectNormalizer(nameConverter: $nameConverter);
        $arrayNormalizer = new ArrayNormalizer($nameConverter, $objectNormalizer);
        $normalizer = new Serializer([
            new CommonDateTimeNormalizer(),
            $objectNormalizer,
            $arrayNormalizer,
        ]);

        $data['data'] = $normalizer->normalize($this->data ?? []);

        foreach ($this->fieldsInfo as $field) {
            $data['fields_info'][] = $field->toArray();
        }

        return new JsonResponse($data);
    }

    public function getFieldsInfo(): array
    {
        return $this->fieldsInfo;
    }

    public function setFieldsInfo(array $fieldsInfo): self
    {
        $this->fieldsInfo = $fieldsInfo;

        return $this;
    }
}
