<?php

namespace Wise\Agreement\ApiUi\Dto\ContractAgreement;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class PostUserAgreeContractDto extends CommonUiApiDto
{
    #[OA\Property(
        description: 'Identyfikator umowy',
        example: 5,
    )]
    protected int $contractId;

    #[OA\Property(
        description: 'Miejsca z którego zgoda została wyrażona',
        example: 'HOME_PAGE',
    )]
    protected string $contextAgreement;

    public function getContractId(): int
    {
        return $this->contractId;
    }

    public function setContractId(int $contractId): self
    {
        $this->contractId = $contractId;

        return $this;
    }

    public function getContextAgreement(): string
    {
        return $this->contextAgreement;
    }

    public function setContextAgreement(string $contextAgreement): self
    {
        $this->contextAgreement = $contextAgreement;

        return $this;
    }
}
