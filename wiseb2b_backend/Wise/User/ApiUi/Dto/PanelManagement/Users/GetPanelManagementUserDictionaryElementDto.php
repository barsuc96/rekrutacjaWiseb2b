<?php

namespace Wise\User\ApiUi\Dto\PanelManagement\Users;

use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;
use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;

class GetPanelManagementUserDictionaryElementDto extends CommonUiApiDto
{
    #[OA\Property(
        description: 'Wartość',
        example: 1,
    )]
    #[FieldEntityMapping('id')]
    protected int $value;

    #[OA\Property(
        description: 'Tekst wyświetlana',
        example: 'PAYU-1234567890',
    )]
    #[FieldEntityMapping('login')]
    protected string $text;

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
