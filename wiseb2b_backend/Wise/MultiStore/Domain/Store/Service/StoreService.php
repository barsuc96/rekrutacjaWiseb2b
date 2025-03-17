<?php

declare(strict_types=1);

namespace Wise\MultiStore\Domain\Store\Service;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Wise\Client\Service\Client\Interfaces\GetClientDetailsServiceInterface;
use Wise\Client\Service\Client\Interfaces\ListClientsServiceInterface;
use Wise\Core\Domain\AbstractEntityDomainService;
use Wise\Core\Domain\ShareMethodHelper\EntityDomainServiceShareMethodsHelper;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\MultiStore\Domain\Store\Exceptions\StoreNotFoundException;
use Wise\MultiStore\Domain\Store\Service\Interfaces\StoreConfigurationHelperInterface;
use Wise\MultiStore\Domain\Store\Service\Interfaces\StoreServiceInterface;
use Wise\MultiStore\Domain\Store\Store;
use Wise\MultiStore\Domain\Store\StoreRepositoryInterface;
use Wise\MultiStore\WiseMultiStoreExtension;

class StoreService extends AbstractEntityDomainService implements StoreServiceInterface
{
    public function __construct(
        private readonly StoreRepositoryInterface $storeRepository,
        private readonly EntityDomainServiceShareMethodsHelper $entityDomainServiceShareMethodsHelper,
        private readonly ContainerBagInterface $configParams,
        private readonly GetClientDetailsServiceInterface $getClientDetailsService,
        private readonly ListClientsServiceInterface $listClientsService,
        private readonly StoreConfigurationHelperInterface $storeConfigurationHelper,
        private readonly Stopwatch $stopwatch
    ) {
        parent::__construct(
            repository: $storeRepository,
            notFoundException: StoreNotFoundException::class,
            entityDomainServiceShareMethodsHelper: $entityDomainServiceShareMethodsHelper
        );
    }

    /**
     * Zwraca obiekt sklepu na podstawie symbolu
     * @param string $symbol
     * @return Store|null
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getStoreBySymbol(string $symbol): ?Store
    {
        $this->stopwatch->start('StoreService::getStoreBySymbol');
        $config = $this->getStoresConfiguration();

        if(isset($config[$symbol])){

            $store = $config[$symbol];
            $this->stopwatch->stop('StoreService::getStoreBySymbol');
            return new Store($store['id'], $store['symbol'], $store['name']);
        }
        $this->stopwatch->stop('StoreService::getStoreBySymbol');

        return null;
    }

    /**
     * Zwraca obiekt sklepu na podstawie identyfikatora sklepu
     * @param int $storeId
     * @return Store|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getStoreById(int $storeId): ?Store
    {
        $config = $this->getStoresConfiguration();

        if(!empty($config)){
            foreach ($config as $store){
                if($store['id'] === $storeId){
                    return new Store($store['id'], $store['symbol'], $store['name']);
                }
            }
        }

        return null;
    }

    /**
     * Zwraca obiekt sklepu na podstawie identyfikatora klienta (store przypisany do klienta)
     * @param int $clientId
     * @return Store[]|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getStoresByClientId(int $clientId): ?array
    {
        $params = new CommonListParams();
        $params
            ->setFilters([
                new QueryFilter('id', $clientId)
            ])
            ->setFields([
                'id' => 'id',
                'clientGroupId.storeId' => 'clientGroupId.storeId',
            ]);

        $clientsData = ($this->listClientsService)($params)->read();

        $stores = null;
        if(!empty($clientsData)){

            foreach ($clientsData as $clientData){
                if(empty($clientData['clientGroupId_storeId'])){
                    continue;
                }

                $store = $this->getStoreById($clientData['clientGroupId_storeId']);

                if($store !== null){
                    $stores[] = $store;
                }
            }
        }

        return $stores;
    }

    /**
     * Metoda wczytująca dane z plików konfiguracyjnych sklepów
     * @return array
     */
    public function getStoresConfiguration(): array
    {
        $config = $this->getConfig();
        return $config['stores'];
    }

    /**
     * Zwraca konfigurację modułu
     * @return array|null
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getConfig(): ?array
    {
        return $this->configParams->get(WiseMultiStoreExtension::getExtensionAlias());
    }
}
