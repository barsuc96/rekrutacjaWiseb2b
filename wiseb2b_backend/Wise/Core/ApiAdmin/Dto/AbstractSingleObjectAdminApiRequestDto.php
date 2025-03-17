<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Dto;

use Wise\Core\Api\Dto\AbstractRequestDto;

/**
 * Abstract do deklarowania DTO parametrów pojedyńczego obiektu PUT i PATCH AdminApi .
 * Klasa bazowa dla DTO parametrów kontrolerów, np. \Wise\Client\ApiAdmin\Dto\Clients\PutClientDtoSingleObject
 * Reprezentuje pojedyńczy obiekt, który ma zostać np. persistowany w bazie danych
 */

class AbstractSingleObjectAdminApiRequestDto extends AbstractRequestDto
{

}
