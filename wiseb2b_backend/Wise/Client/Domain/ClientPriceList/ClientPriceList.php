<?php

declare(strict_types=1);

namespace Wise\Client\Domain\ClientPriceList;

use Symfony\Component\Serializer\Annotation\Ignore;
use Wise\Client\Domain\ClientGroup\ClientGroup;
use Wise\Core\Model\AbstractModel;

class ClientPriceList extends AbstractModel
{
    protected ?int $clientGroupId = null;
    protected ?int $priority = null;
    protected ?int $priceListId = null;
    protected ?int $storeId = null;

    #[Ignore]
    protected ?ClientGroup $clientGroup = null;

    /**
     * @param ClientGroup|null $clientGroup
     */
    public function __construct(
        ?ClientGroup $clientGroup = null,
    ) {
        $this->clientGroup = $clientGroup;
    }

    /**
     * @return ClientGroup|null
     */
    #[Ignore]
    public function getClientGroup(): ?ClientGroup
    {
        return $this->clientGroup;
    }

    #[Ignore]
    public function setClientGroup(?ClientGroup $clientGroup): self
    {
        $this->clientGroup = $clientGroup;
        $this->clientGroupId = $clientGroup->getId();

        return $this;
    }

    /**
     * @return int|null
     */
    public function getClientGroupId(): ?int
    {
        return $this->clientGroupId;
    }

    /**
     * @param int|null $clientGroupId
     * @return self
     */
    public function setClientGroupId(?int $clientGroupId): self
    {
        $this->clientGroupId = $clientGroupId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPriority(): ?int
    {
        return $this->priority;
    }

    /**
     * @param int|null $priority
     * @return self
     */
    public function setPriority(?int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPriceListId(): ?int
    {
        return $this->priceListId;
    }

    /**
     * @param int|null $priceListId
     * @return self
     */
    public function setPriceListId(?int $priceListId): self
    {
        $this->priceListId = $priceListId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getStoreId(): ?int
    {
        return $this->storeId;
    }

    /**
     * @param int|null $storeId
     * @return $this
     */
    public function setStoreId(?int $storeId): self
    {
        $this->storeId = $storeId;

        return $this;
    }
}
