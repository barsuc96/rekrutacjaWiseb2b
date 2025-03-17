<?php

namespace Wise\Core\ApiUi\Dto;

use Wise\Core\Dto\AbstractResponseDto;

use OpenApi\Attributes as OA;
class CommonDictionaryElementResponseDto extends AbstractResponseDto
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
    protected null|string|int|bool $value;

    public function getValue(): bool|int|string|null
    {
        return $this->value;
    }

    public function setValue(bool|int|string|null $value): self
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
