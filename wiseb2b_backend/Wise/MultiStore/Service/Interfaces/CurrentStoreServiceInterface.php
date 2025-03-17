<?php

namespace Wise\MultiStore\Service\Interfaces;
use Wise\MultiStore\Service\Store\StoreInfo;

interface CurrentStoreServiceInterface
{
    /**
     * Zwraca obiekt obecnie przeglądanej witryny
     */
    public function getCurrentStore(): StoreInfo;

    /**
     * Zwraca identyfikator aktualnego sklepu
     */
    public function getCurrentStoreId(): ?int;

    /**
     * Zwraca informacje o store na podstawie id
     */
    public function getStoreById(int $storeId): ?StoreInfo;

    /**
     * metoda do ustawiania symbolu sklepu przez zewnętrzny serwis
     */
    public function setStore(string $storeSymbol): void;
    public function setCurrentStore(int $id): void;
}
