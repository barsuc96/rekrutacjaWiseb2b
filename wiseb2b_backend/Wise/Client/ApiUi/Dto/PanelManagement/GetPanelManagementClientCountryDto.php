<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto\PanelManagement;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\Api\Fields\FieldHandlingEnum;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class GetPanelManagementClientCountryDto extends CommonUiApiDto
{
    #[OA\Property(
        description: 'Tekst elementu, do wyświetlenia dla użytkownika',
        example: 'Przykładowy tekst wyświetlany na liście',
    )]
    protected ?string $text;


    #[OA\Property(
        description: 'Wartość elementu (select). Ta wartość jest później przekazywana do komend',
        example: 1,
    )]
    protected ?string $value;

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }
}

