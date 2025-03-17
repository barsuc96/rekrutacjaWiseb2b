<?php

namespace Wise\MultiStore\Service\Store;

use Wise\MultiStore\Domain\Store\Store;

class StoreInfo
{
    protected int $id;
    protected string $symbol;
    protected ?string $name = null;

    public static function fromStore(Store $store): self
    {
        $storeInfo = new self();
        if ($store->getId())
            $storeInfo->setId($store->getId());
        $storeInfo->setSymbol($store->getSymbol());
        $storeInfo->setName($store->getName());

        return $storeInfo;
    }


    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of symbol
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * Set the value of symbol
     *
     * @return  self
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
