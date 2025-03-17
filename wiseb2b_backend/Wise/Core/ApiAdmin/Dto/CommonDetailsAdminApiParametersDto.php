<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\Dto\AbstractDto;

/**
 * Abstract do deklarowania DTO parametrów / filtrów dla endpointów zwracających pojedyńczy obiekt w AdminApi
 * Dodaje informację o identyfikatorze encji z ERP do możliwych filtrów endpointa.
 * Przykład użycia: \Wise\Client\ApiAdmin\Dto\Clients\GetClientParametersAdminApiParametersDto
 */
class CommonDetailsAdminApiParametersDto extends AbstractDto
{
    #[OA\Parameter(description: 'Identyfikator encji z ERP', in: 'path', example: 1)]
    protected string $id;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }


}
