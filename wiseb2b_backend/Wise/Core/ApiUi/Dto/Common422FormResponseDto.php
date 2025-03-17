<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * TODO: [ws] dodać opis, nie wiem jak to działa
 */
class Common422FormResponseDto extends CommonFormResponseDto
{
    /** @var list<FieldInfoDto> $fieldsInfo */
    #[OA\Property(description: 'Lista pól do poprawy')]
    protected array $fieldsInfo = [];

    #[OA\Property(description: 'Czy wyświetlić wiadomość fieldInfos', example: false)]
    protected bool $showFieldInfos = true;


    public function getFieldsInfo(): array
    {
        return $this->fieldsInfo;
    }

    /** @param list<FieldInfoDto> $errorFields */
    public function setFieldsInfo(array $fieldsInfo): self
    {
        $this->fieldsInfo = $fieldsInfo;

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

        foreach ($this->fieldsInfo as $field) {
            $data['fields_info'][] = $field->toArray();
        }

        return new JsonResponse($data, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
