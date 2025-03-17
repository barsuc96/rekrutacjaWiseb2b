<?php

namespace Wise\User\ApiUi\Dto\PanelManagement\Users;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiUi\Dto\CommonParameters\CommonParametersDto;

class PostPanelManagementUserAgreeContractDto extends CommonParametersDto
{
    #[OA\Path(
        description: 'Identyfikator użytkownika',
        example: 1,
    )]
    protected int $userId;

    #[OA\Path(
        description: 'Identyfikator umowy',
        example: 1,
    )]
    protected int $contractId;

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getContractId(): int
    {
        return $this->contractId;
    }

    public function setContractId(int $contractId): self
    {
        $this->contractId = $contractId;

        return $this;
    }

}
