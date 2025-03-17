<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Dto;

use Wise\Core\Dto\AbstractDto;
use OpenApi\Attributes as OA;

/**
 * TODO: [ws] dodać opis, nie wiem jak to działa
 * @deprecated Już nie jest potrzebne (nowe endpointy nie powinny z tego dziedziczyć)
 */
abstract class CommonQueryParametersDto extends AbstractDto
{
    #[OA\Parameter(description: 'Wersja językowa', in: 'header', example: 'pl')]
    protected string $contentLanguage;

    public function getContentLanguage(): string
    {
        return $this->contentLanguage;
    }

    public function setContentLanguage(string $contentLanguage): self
    {
        $this->contentLanguage = $contentLanguage;

        return $this;
    }
}
