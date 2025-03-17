<?php

namespace Wise\Agreement\ApiUi\Dto\Contract;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiUi\Dto\CommonUiApiListResponseDto;

class GetContractsDto extends CommonUiApiListResponseDto
{
    #[OA\Query(
        description: 'Kontekst umowy',
        example: 'HOME_PAGE',
    )]
    protected ?string $context;

    #[OA\Query(
        description: 'Kontekst umowy - aktualny (na podstawie tej wartości określamy jakie umowy wymagają akceptacji przez użytkownika)',
        example: 'HOME_PAGE',
    )]
    protected ?string $currentContext;

    #[OA\Query(
        description: 'Identyfikator koszyka',
        example: 1,
    )]
    protected ?int $cartId;

    #[OA\Query(
        description: 'Tylko wymagane',
        example: false,
    )]
    protected ?bool $onlyMustAccept;

    /** @var GetContractDto[] */
    protected ?array $items;

    public function getItems(): ?array
    {
        return $this->items;
    }

    public function setItems(?array $items): self
    {
        $this->items = $items;

        return $this;
    }
}
