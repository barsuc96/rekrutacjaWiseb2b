<?php

namespace Wise\Client\ApiAdmin\Dto\ClientGroups;

use Wise\Core\Api\Attributes\OpenApi\EndpointElement as OA;
use Wise\Core\ApiAdmin\Dto\CommonAdminApiDto;

class ClientGroupPriceListDto extends CommonAdminApiDto
{
    #[OA\Property(
        description: 'Id cennika z systemu zewnętrznego typu ERP',
        example: 'STANDARD',
    )]
    protected ?string $priceListId;

    #[OA\Property(
        description: 'ID wewnętrzne systemu cennika B2B',
        example: 1,
    )]
    protected ?int $priceListInternalId;

    #[OA\Property(
        description: 'Priorytet',
        example: 10,
    )]
    protected ?int $priority;

    #[OA\Property(
        description: 'Id sklepu, który jest przypisany do grupy klienckiej',
        example: 1,
    )]
    protected ?int $storeId;

    public function getPriceListId(): ?string
    {
        return $this->priceListId;
    }

    public function setPriceListId(?string $priceListId): self
    {
        $this->priceListId = $priceListId;

        return $this;
    }

    public function getPriceListInternalId(): ?int
    {
        return $this->priceListInternalId;
    }

    public function setPriceListInternalId(?int $priceListInternalId): self
    {
        $this->priceListInternalId = $priceListInternalId;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getStoreId(): ?int
    {
        return $this->storeId;
    }

    public function setStoreId(?int $storeId): self
    {
        $this->storeId = $storeId;

        return $this;
    }


}
