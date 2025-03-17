<?php

declare(strict_types=1);

namespace Wise\Client\Domain\ClientStatus;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Wise\Client\Domain\ClientStatus\Exceptions\ClientStatusNotExistException;
use Wise\Client\Domain\ClientStatus\Factory\ClientStatusFactory;
use Wise\Client\Service\Client\Interfaces\ListClientStatusServiceInterface;
use Wise\Client\WiseClientExtension;
use Wise\Core\Exception\InvalidInputArgumentException;
use Wise\Core\Exception\ObjectValidationException;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;

class ClientStatusService implements ClientStatusServiceInterface
{
    public function __construct(
        private readonly ContainerBagInterface $configParams,
        private readonly ClientStatusFactory $statusFactory,
        private readonly ListClientStatusServiceInterface $listClientStatusService
    ) {
    }

    /**
     * Metoda zwraca listę statusów zamówień
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getClientStatuses(): array
    {
        $statusConfigs = $this->loadConfiguration() ?? [];
        $statutes = [];
        foreach ($statusConfigs as $status => $statusConfig) {
            $statutes[] = $this->statusFactory->create([
                'symbol' => $status,
                'id' => $statusConfig['id'],
            ]);
        }

        return $statutes;
    }

    /**
     * Metoda zwraca status zamówienia na podstawie symbolu
     * @param $symbol
     * @return ClientStatus|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getClientStatus($symbol): ?ClientStatus
    {
        $statusConfigs = $this->loadConfiguration() ?? [];
        foreach ($statusConfigs as $status => $statusConfig) {
            if ($status === $symbol) {
                return $this->statusFactory->create([
                    'symbol' => $status,
                    'id' => $statusConfig['status_number'],
                ]);
            }
        }
        return null;
    }

    /**
     * Metoda sprawdza, czy istnieje status zamówienia na podstawie symbolu lub numeru statusu.
     * @param string|int $status
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function existOrderStatus(string|int $status): bool
    {
        $statusConfigs = $this->loadConfiguration() ?? [];

        // Jeśli jest stringiem weryfikuje na podstawie nazwy statusu np. NEW
        if(is_string($status)){
            return array_key_exists($status, $statusConfigs);
        }

        foreach ($statusConfigs as $statusConfig) {
            if ($statusConfig['status_number'] === $status) {
                return true;
            }
        }
        return false;
    }

    /**
     * Metoda zwraca status zamówienia na podstawie numeru statusu
     * @param $symbolNumber
     * @return ClientStatus|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getClientStatusByStatusNumber($symbolNumber): ?ClientStatus
    {
        $statusConfigs = $this->loadConfiguration() ?? [];
        foreach ($statusConfigs as $status => $statusConfig) {
            if ($statusConfig['status_number'] === $symbolNumber) {
                return $this->statusFactory->create([
                    'symbol' => $status,
                    'id' => $statusConfig['status_number'],
                ]);
            }
        }
        return null;
    }

    /**
     * Zwraca status, jeśli istnieje na podstawie danych
     * @param int|null $statusId
     * @param array|null $statusData
     * @return int|null
     * @throws InvalidInputArgumentException
     */
    public function getStatusIdIfExistsByData(?int $statusId = null, ?array $statusData = null): ?int
    {
        $status = null;

        // Pobranie na podstawie przekazanego id
        if($statusId !== null){
            $params = new CommonListParams();
            $params
                ->setFilters([
                    new QueryFilter('id', $statusId)
                ])
                ->setFields([]);
            $status = ($this->listClientStatusService)($params)->read();
        }

        // Jeśli nie znaleziono na podstawie id, zrób to na podstawie data
        if(empty($status) && !empty($statusData)){
            $filters = [];

            // Przygotowanie filtrów na podstawie danych
            foreach ($statusData as $field => $value){
                $filters[] = new QueryFilter($field, $value);
            }

            $params = new CommonListParams();
            $params
                ->setFilters($filters)
                ->setFields([]);
            $status = ($this->listClientStatusService)($params)->read();
        }

        if(empty($status)){
            $params = new CommonListParams();
            $params
                ->setFilters([])
                ->setFields([]);
            $statuses = ($this->listClientStatusService)($params)->read();

            throw (new ClientStatusNotExistException())->setTranslationParams(['%statuses%' => implode(', ', array_column($statuses, 'status'))]);
        }

        if(count($status) > 1){
            throw new InvalidInputArgumentException('Znaleziono więcej niż jeden obiekt ClientStatus');
        }

        $status = reset($status);

        // Weryfikacja czy dane pasują do siebie
        if(!empty($statusData)){
            foreach ($statusData as $field => $value){
                if(isset($status[$field]) && $status[$field] !== $value){
                    throw new ObjectValidationException('Znaleziono obiekt ClientStatus lecz dane przekazane w request nie zą zbierzne z pobranym obiektem. Pole: ' . $field . '(' . $status[$field]  . ' => ' . $value . ' )');
                }
            }
        }

        return $status['id'];
    }

    /**
     * Metoda zwraca listę statusów zamówień z pliku konfiguracyjnego
     * @return array Lista statusów zamówień
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function loadConfiguration(): array
    {
        $serviceConfig = $this->configParams->get(WiseClientExtension::getExtensionAlias());

        // Statusy zamówień ustalane są w pliku konfiguracyjnym
        return $serviceConfig['client_status'];
    }

}
