<?php

namespace Wise\User\ApiUi\Dto\PanelManagement\Traders;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class GetPanelManagementTradersDictionaryItemDto extends CommonUiApiDto
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
    protected ?int $value;

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(?int $value): self
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
