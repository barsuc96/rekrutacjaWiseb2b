<?php

namespace Wise\GPSR\ApiUi\Dto\PanelManagement;


use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiUi\Dto\CommonUiApiListResponseDto;

class GetPanelManagementSuppliersDto extends CommonUiApiListResponseDto
{
    #[OA\Query(
        description: 'Wyszukiwarka',
        example: 'URSUS',
    )]
    protected ?int $searchKeyword = null;

    #[OA\Query(
        description: 'Czy odbiorca jest aktywny',
        example: true,
    )]
    protected ?bool $isActive = null;

    /** @var GetPanelManagementSupplierDto[] */
    protected ?array $items;

    public function getSearchKeyword(): ?int
    {
        return $this->searchKeyword;
    }

    public function setSearchKeyword(?int $searchKeyword): self
    {
        $this->searchKeyword = $searchKeyword;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }


}

