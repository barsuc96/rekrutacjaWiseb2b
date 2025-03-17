<?php

namespace Wise\Agreement\ApiUi\Dto\ContractAgreement;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiUi\Dto\CommonUiApiDto;

class PostUserContractToggleDto extends CommonUiApiDto
{
    #[OA\Property(
        description: 'Identyfikator umowy',
        example: 5,
    )]
    protected int $contractId;

    #[OA\Property(
        description: 'Identyfikator koszyka - Oddziaływanie na zamówienie',
        example: 45,
    )]
    protected ?int $cartId;

    #[OA\Property(
        description: 'Miejsca z którego zgoda została wyrażona',
        example: 'HOME_PAGE',
    )]
    protected string $contextAgreement;

    #[OA\Property(
        description: 'Czy zgoda została wyrażona',
        example: true,
    )]
    protected bool $isAgree;

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

    public function isAgree(): bool
    {
        return $this->isAgree;
    }

    public function setIsAgree(bool $isAgree): self
    {
        $this->isAgree = $isAgree;

        return $this;
    }

    public function getCartId(): ?int
    {
        return $this->cartId;
    }

    public function setCartId(?int $cartId): self
    {
        $this->cartId = $cartId;

        return $this;
    }
}
