<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiUi\Dto;

use OpenApi\Attributes as OA;
use Wise\Core\ApiUi\Dto\CommonGetUiApiDto;

class GetReceiversQueryParametersDto extends CommonGetUiApiDto
{
    #[OA\Property(
        description: 'Filtrowanie po wszystkich polach',
        example: 'Adam',
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
