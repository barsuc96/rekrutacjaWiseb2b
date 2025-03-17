<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\ApiUi\Dto\CommonGetUiApiDto;

class GetOverloginUsersQueryParametersDto extends CommonGetUiApiDto
{
    #[OA\Property(
        description: 'Filtrowania po nazwie klienta/użytkownika',
        example: 'Firma S.A',
    )]
    protected string $searchKeyword;

    public function getSearchKeyword(): string
    {
        return $this->searchKeyword;
    }

    public function setSearchKeyword(string $searchKeyword): self
    {
        $this->searchKeyword = $searchKeyword;

        return $this;
    }
}
