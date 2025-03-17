<?php

namespace Wise\Client\ApiAdmin\Dto\ClientGroups;

use OpenApi\Attributes as OA;
use Wise\Core\Api\Dto\Attributes\FieldEntityMapping;
use Wise\Core\ApiAdmin\Dto\AbstractSingleObjectAdminApiRequestDto;

class PutClientGroupDto extends AbstractSingleObjectAdminApiRequestDto
{
    #[OA\Property(
        description: 'Id grupy klienckiej z systemu zewnętrznego typu ERP',
        example: 'STANDARD',
    )]
    #[FieldEntityMapping('idExternal')]
    protected ?string $id;

    #[OA\Property(
        description: 'ID wewnętrzne systemu B2B',
        example: 1,
    )]
    #[FieldEntityMapping('id')]
    protected ?int $internalId;

    #[OA\Property(
        description: 'Nazwa grupy klienckiej',
        example: 'Standardowa grupa klientów',
    )]
    protected ?string $name;

    #[OA\Property(
        description: 'Id sklepu, który jest przypisany do grupy klienckiej',
        example: 1,
    )]
    protected ?int $storeId;

    #[OA\Property(
        description: 'Czy grupa kliencka jest aktywna?',
        example: false,
    )]
    protected bool $isActive;

    /**
     * @var ClientGroupPriceListDto[]
     */
    protected ?array $priceLists = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getInternalId(): ?int
    {
        return $this->internalId;
    }

    public function setInternalId(?int $internalId): self
    {
        $this->internalId = $internalId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    public function getPriceLists(): ?array
    {
        return $this->priceLists;
    }

    public function setPriceLists(?array $priceLists): self
    {
        $this->priceLists = $priceLists;

        return $this;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }


}
