<?php

namespace Wise\Client\Domain\ClientStatus;

interface ClientStatusServiceInterface
{
    /**
     * Metoda zwraca listę statusów zamówień
     * @return array
     */
    public function getClientStatuses(): array;

    /**
     * Metoda zwraca status zamówienia na podstawie symbolu
     * @param $symbol
     * @return ClientStatus|null
     */
    public function getClientStatus($symbol): ?ClientStatus;

    /**
     * Metoda zwraca status zamówienia na podstawie numeru statusu
     * @param $symbolNumber
     * @return ClientStatus|null
     */
    public function getClientStatusByStatusNumber($symbolNumber): ?ClientStatus;

    /**
     * Metoda sprawdza, czy istnieje status zamówienia na podstawie symbolu lub numeru statusu.
     * @param string|int $status
     * @return bool
     */
    public function existOrderStatus(string|int $status): bool;
}
