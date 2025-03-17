<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\Users;

use OpenApi\Attributes as OA;
use Wise\Core\ApiUi\Dto\CommonGetUiApiDto;

class GetUsersQueryParametersDto extends CommonGetUiApiDto
{
    #[OA\Property(
        description: 'Wyszukiwanie według frazy',
        example: 'Przykładowo: 11-100-HP0110-000, Kimbo',
    )]
    protected string $searchKeyword;

    #[OA\Property(
        description: 'Filtrowanie według roli',
        example: 'ROLE_USER',
    )]
    protected ?string $role;

    public function getSearchKeyword(): string
    {
        return $this->searchKeyword;
    }

    public function setSearchKeyword(string $searchKeyword): self
    {
        $this->searchKeyword = $searchKeyword;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }
}
