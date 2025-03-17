<?php

declare(strict_types=1);

namespace Wise\Client\Domain\ClientGroup;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Wise\Client\Domain\ClientGroup\Event\ClientGroupHasChangedEvent;
use Wise\Client\Domain\ClientPriceList\ClientPriceList;
use Wise\Core\Domain\Event\DomainEventManager;
use Wise\Core\Entity\AbstractEntity;

/**
 * Grupa kliencka
 */
class ClientGroup extends AbstractEntity
{
    /**
     * Identyfikator zewnętrzny z systemu klienta
     * @var string|null
     */
    protected ?string $idExternal = null;

    /**
     * Nazwa grupy
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * Identyfikator sklepu, do którego przypisana jest grupa klientów
     * @var int|null
     */
    protected ?int $storeId = null;


    /** @var Collection<array-key, ClientPriceList> $priceLists */
    protected Collection $priceLists;

    public function __construct()
    {
        $this->priceLists = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getIdExternal(): ?string
    {
        return $this->idExternal;
    }

    /**
     * @param string|null $idExternal
     * @return self
     */
    public function setIdExternal(?string $idExternal): self
    {
        $this->idExternal = $idExternal;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPriceLists(): Collection
    {
        return $this->priceLists;
    }

    /**
     * @param Collection $priceLists
     * @return self
     */
    public function setPriceLists(Collection $priceLists): self
    {
        $this->priceLists = $priceLists;

        return $this;
    }

    /**
     * @param ClientPriceList $clientPriceList
     * @return self
     */
    public function addClientPriceList(ClientPriceList $clientPriceList): self
    {
        $this->priceLists->add($clientPriceList);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getClientPriceList(): Collection
    {
        return $this->priceLists;
    }

    /**
     * @param Collection $priceLists
     * @return self
     */
    public function setClientPriceList(Collection $priceLists): self
    {
        $this->priceLists = $priceLists;

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

    protected function entityHasChanged(string $newHash): void
    {
        parent::entityHasChanged($newHash);

        DomainEventManager::instance()->post(new ClientGroupHasChangedEvent($this->getId()));
    }

    public function findClientPriceListPositionByPriceListId(int $priceListId): ?ClientPriceList
    {
        return $this->getPriceLists()?->findFirst(fn(int $i, ClientPriceList $p): bool => $p->getPriceListId() === $priceListId);
    }

    public function removePriceList(ClientPriceList $clientPriceList): void
    {
        $this->getPriceLists()->removeElement($clientPriceList);
    }
}
