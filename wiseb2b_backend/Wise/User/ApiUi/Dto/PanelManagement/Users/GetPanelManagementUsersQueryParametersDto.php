<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Dto\PanelManagement\Users;

use OpenApi\Attributes as OA;
use Wise\Core\ApiUi\Dto\CommonGetUiApiDto;

class GetPanelManagementUsersQueryParametersDto extends CommonGetUiApiDto
{
    #[OA\Property(
        description: 'Filtrowanie po polach: symbol oraz id',
        example: 'example',
    )]
    protected ?string $searchKeyword;

    #[OA\Property(
        description: 'Filtrowanie po identyfikatorze klienta',
        example: 3,
    )]
    protected ?int $clientId;

    public function getSearchKeyword(): ?string
    {
        return $this->searchKeyword;
    }

    public function setSearchKeyword(?string $searchKeyword): self
    {
        $this->searchKeyword = $searchKeyword;

        return $this;
    }

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }
}
