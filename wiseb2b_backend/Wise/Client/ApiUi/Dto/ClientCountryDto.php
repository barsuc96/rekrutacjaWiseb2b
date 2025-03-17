<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\Api\Dto\CommonParameterListTrait;
use Wise\Core\Dto\AbstractResponseDto;

class ClientCountryDto extends AbstractResponseDto
{
    use CommonParameterListTrait;

    #[OA\Property(
        description: 'Kod kraju',
        example: 'DE',
    )]
    #[FieldEntityMapping('idExternal')]
    protected string $code;

    #[OA\Property(
        description: 'Nazwa kraju',
        example: 'Niemcy',
    )]
    protected string $name;

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): ClientCountryDto
    {
        $this->code = $code;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): ClientCountryDto
    {
        $this->name = $name;
        return $this;
    }
}
