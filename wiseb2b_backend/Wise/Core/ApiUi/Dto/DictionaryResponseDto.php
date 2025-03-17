<?php

namespace Wise\Core\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractResponseDto;

class DictionaryResponseDto extends AbstractResponseDto
{
    #[OA\Property(
        description: 'Id sekcji',
        example: 1,
    )]
    protected int|string|null $value;

    #[OA\Property(
        description: 'Id sekcji',
        example: 1,
    )]
    protected string $text;

    public function getValue(): int|string|null
    {
        return $this->value;
    }

    public function setValue(int|string|null $value): self
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
