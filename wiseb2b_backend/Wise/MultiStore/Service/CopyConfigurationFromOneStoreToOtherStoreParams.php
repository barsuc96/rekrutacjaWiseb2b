<?php

declare(strict_types=1);

namespace Wise\MultiStore\Service;

use Wise\Core\Dto\CommonServiceDTO;

class CopyConfigurationFromOneStoreToOtherStoreParams extends CommonServiceDTO
{
    const FILTR_OAUTH = 'oauth';
    const FILTR_CLIENT = 'client';
    const FILTR_CLIENT_GROUP = 'client_group';
    const FILTR_USERS_AND_OPEN_PROFILE = 'users_and_open_profile';
    const FILTR_DELIVERY_AND_PAYMENT = 'delivery_and_payment';
    const FILTR_PRICE_LIST = 'price_list';
    const FILTR_CATEGORY = 'category';
    const FILTR_PRODUCT_CATALOG = 'product_catalog';
    const SECTIONS_AND_ARTICLES = 'sections_and_articles';
    const FILTR_ALL = 'all';



    /**
     * Co ma zostać przeliczone
     * @var string
     * @example 'client|client_group|store|all'
     */
    protected string $flags = 'all';


    /**
     * Symbol sklepu źródłowego
     * @var string|null
     */
    protected ?string $fromStoreSymbol = null;

    /**
     * Symbol sklepu na, który chcemy skopiować dane
     * @var string|null
     */
    protected ?string $toStoreSymbol = null;

    /**
     * Suffix dodawany do symboli/kluczy do łatwiejszego rozróżnienia
     * @var string|null
     * @example "_momenti"
     */
    protected ?string $suffix = null;

    public function getFromStoreSymbol(): ?string
    {
        return $this->fromStoreSymbol;
    }

    public function setFromStoreSymbol(string $fromStoreSymbol): self
    {
        $this->fromStoreSymbol = $fromStoreSymbol;

        return $this;
    }

    public function getToStoreSymbol(): ?string
    {
        return $this->toStoreSymbol;
    }

    public function setToStoreSymbol(string $toStoreSymbol): self
    {
        $this->toStoreSymbol = $toStoreSymbol;

        return $this;
    }

    public function getSuffix(): ?string
    {
        return $this->suffix;
    }

    public function setSuffix(?string $suffix): self
    {
        $this->suffix = $suffix;

        return $this;
    }

    public function getFlags(): string
    {
        return $this->flags;
    }

    public function setFlags(string $flags): self
    {
        $this->flags = $flags;

        return $this;
    }

    public function getFlirtsArray(): array
    {
        return explode('|', $this->flags);
    }
}
