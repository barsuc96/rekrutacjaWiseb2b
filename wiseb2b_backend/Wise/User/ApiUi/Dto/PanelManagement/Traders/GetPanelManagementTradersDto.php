<?php

namespace Wise\User\ApiUi\Dto\PanelManagement\Traders;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiUi\Dto\CommonUiApiListResponseDto;

class GetPanelManagementTradersDto extends CommonUiApiListResponseDto
{
    #[OA\Query(
        description: 'Identyfikator',
        example: 3,
    )]
    protected ?int $id;

    /** @var GetPanelManagementTraderDto[] */
    protected ?array $items;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }
}
