<?php

namespace Wise\Agreement\ApiUi\Dto\PanelManagement;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\ApiUi\Dto\CommonParameters\CommonParametersDto;

class PostPanelManagementWithdrawalContractDto extends CommonParametersDto
{
    #[OA\Path(
        description: 'Identyfikator umowy',
        example: 5,
    )]
    #[FieldEntityMapping('id')]
    protected int $contractId;

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
