<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractDto;

/**
 * ## Klasa pomocnicza dla DTO parametrów w ADMINAPI - DELETE
 * Podstawowe pole wymagane do poprawnego działania endpointów usuwający obiekt
 */
class CommonDeleteAdminApiParametersDto extends CommonAdminApiDto
{
    #[OA\Parameter(
        description: 'Id z zewnętrznego systemu',
        in: 'path',
        example: '3'
    )]
    private string $idExternal;

    public function getIdExternal(): string
    {
        return $this->idExternal;
    }

    public function setIdExternal(string $idExternal): self
    {
        $this->idExternal = $idExternal;

        return $this;
    }


}
