<?php

declare(strict_types=1);

namespace Wise\MultiStore\Service;
use Doctrine\Persistence\ManagerRegistry;
use League\Bundle\OAuth2ServerBundle\Entity\AccessToken;
use PDO;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Webmozart\Assert\Assert;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Service\DomainEventsDispatcher;
use Wise\Core\Service\SessionParamServiceInterface;
use Wise\MultiStore\Domain\Store\Service\Interfaces\StoreServiceInterface;
use Wise\MultiStore\Service\Interfaces\CurrentStoreServiceInterface;
use Wise\MultiStore\Service\Interfaces\CurrentStoreSymbolServiceInterface;
use Wise\MultiStore\WiseMultiStoreExtension;
use Wise\MultiStore\Service\Store\StoreInfo;
use Wise\Security\Repository\Doctrine\AccessTokenRepository;
use Wise\Security\WiseSecurityExtension;


/**
 * Serwis zwraca obiekt obecnie zalogowanego użytkownika
 */
class CurrentStoreService implements CurrentStoreServiceInterface
{
    protected ?StoreInfo $currentStore = null;

    public function __construct(
        private readonly ContainerBagInterface $configParams,
        private readonly StoreServiceInterface $storeService,
        private RequestStack $requestStack,
        private readonly DomainEventsDispatcher $eventsDispatcher,
        private readonly SessionParamServiceInterface $sessionParamService,
        private readonly Security $security,
        #[TaggedIterator('multi_store.current_store_symbol')]
        private readonly iterable $providers,
        private readonly AccessTokenRepository $accessTokenRepository,
        private readonly ManagerRegistry $registry
    ) {
        Assert::allIsInstanceOf($providers, CurrentStoreSymbolServiceInterface::class);
    }

    public function getCurrentStore(): StoreInfo
    {
        if ($this->currentStore !== null) {
            return $this->currentStore;
        }
        //1. TODO: Sprawdzam zmienne sesyjne Wise, czy jest w nich symbol sklepu. Jak jest, to go zwracam.
        /**
         * jeżeli jest ustawiony w sesji to taki zwracamy
         */
        $store = $this->getStoreFromSession();

        if ($store !== null) {
            $this->currentStore = $store;
            return $store;
        }

        //2. sprawdzam po kluczu api_client_id
        /**
         * jeżeli nie ma w sesji to próbujemy ustawić na podstawie oauthApiClientId
         */
        // Pobieramy symbol z sesji (try catch to wytrych dla komend, testów)
        $store = $this->getStoreFromOauthApiClientId();

        if ($store !== null) {
            $this->setStore($store->getSymbol());
            $this->currentStore = $store;
            return $store;
        }


        //3. Jeśli dalej nieokreślone, to odpytuje moduły, aby ktoś możę określił, jaki mamy aktualny sklep. Wykrozystuje m.in. ClientAPI
        $store = $this->getStoreFromProviders();

        if ($store !== null) {
            $this->setStore($store->getSymbol());
            $this->currentStore = $store;
            return $store;
        }

        /**
         * jeżeli nie to zwracamy domyślny sklep z konfiguracji
         */
        $store = $this->getDefaultStore();

        if ($store !== null) {
            // specjalnie nie ustawiam w sesji domyślnego, bo wydaje mi się, że
            // niektóre pierwsze requesty mogą tutaj wpaść bez sesji, ale za chwilę
            // inny request już będzie miał sklep ustawiony z configa albo z providera
            return $store;
        }

        throw new CommonLogicException('Nie określono sklepu');
    }

    protected function getStoreFromSession(): ?StoreInfo
    {
        /**
         * jeżeli nie ma usera to nie będzie też sesji
         *
         * nie umiem skorzystać z CurrentUserService, bo tam leci fatal error, nie da się wyjątku przechwycić
         *
         */
        try {
            $this->requestStack->getSession();
            if ($this->security->getUser() === null)
            {
                return null;
            }
        }
        catch (\Exception $e) {
            return null;
        }

        try{
            $storeSymbol = $this->sessionParamService->getActiveSessionParam(WiseMultiStoreExtension::CURRENT_STORE_SYMBOL);

            if ($storeSymbol !== null) {
                $store = $this->storeService->getStoreBySymbol($storeSymbol->getValue());
                if ($store !== null) {
                    return StoreInfo::fromStore($store);
                }
            }

        }catch (\Exception $e){}

        return null;
    }

    protected function getStoreFromOauthApiClientId(): ?StoreInfo
    {
        $oauthClientId = null;

        try{
            $session = $this->requestStack->getSession();
            $oauthClientId = $session->get(WiseSecurityExtension::OAUTH_API_CLIENT_ID_SESSION_PARAM);
        }catch (\Exception $e){}

        if ($oauthClientId === null) {
            return null;
        }

        $store = $this->getStoreConfigurationForApiClientId($oauthClientId);

        if ($store === null) {
            return null;
        }

        return $store;
    }

    protected function getStoreFromProviders(): ?StoreInfo
    {
        foreach ($this->providers as $provider) {
            $storeSymbol = $provider();
            if ($storeSymbol !== null) {
                $store = $this->storeService->getStoreBySymbol($storeSymbol);
                if ($store !== null) {
                    return StoreInfo::fromStore($store);
                }
            }
        }

        return null;
    }

    protected function getDefaultStore(): ?StoreInfo
    {
        $storeSymbol = $this->getDefaultStoreSymbolFromConfiguration();
        $store = $this->storeService->getStoreBySymbol(symbol: $storeSymbol);

        if ($store === null) {
            return null;
        }

        return StoreInfo::fromStore($store);
    }

    /**
     * Zwraca identyfikator aktualnego sklepu
     * @return int|null
     */
    public function getCurrentStoreId(): ?int
    {
        $store = $this->getCurrentStore();

        return $store->getId();
    }

    protected function getDefaultStoreSymbolFromConfiguration()
    {
        $config = $this->configParams->get(WiseMultiStoreExtension::getExtensionAlias());

        return $config['default_store_symbol'];
    }

    protected function getStoreConfigurationForApiClientId(string $oauthClientId): ?StoreInfo
    {
        $config = $this->configParams->get(WiseMultiStoreExtension::getExtensionAlias());
        $clientStore = array_filter($config['api_client_to_store'], function($store) use ($oauthClientId) {
            return $store == $oauthClientId;
        }, ARRAY_FILTER_USE_KEY);

        if (empty($clientStore)) {
            return null;
        }

        $clientStore = reset($clientStore);
        return StoreInfo::fromStore($this->storeService->getStoreBySymbol(symbol: $clientStore['store_symbol']));
    }

    public function setStore(string $storeSymbol): void
    {
        /**
         * jeżeli nie ma usera to nie będzie też sesji
         *
         * nie umiem skorzystać z CurrentUserService, bo tam leci fatal error, nie da się wyjątku przechwycić
         *
         */
        try {
            $this->requestStack->getSession();
            if ($this->security->getUser() === null)
            {
                return;
            }
        }
        catch (SessionNotFoundException $e) {
            return;
        }

        try {
            $this->sessionParamService->setSessionParam(WiseMultiStoreExtension::CURRENT_STORE_SYMBOL, $storeSymbol);
        }
        catch (\Exception $e) {}
    }

    public function setCurrentStore(int $id): void
    {
        $this->currentStore = StoreInfo::fromStore($this->storeService->getStoreById($id));
    }

    /**
     * Zwraca obiekt sklepu na podstawie id
     * @param int $storeId
     * @return StoreInfo|null
     */
    public function getStoreById(int $storeId): ?StoreInfo
    {
        $store = $this->storeService->getStoreById($storeId);
        if($store === null){
            return null;
        }

        return StoreInfo::fromStore($store);
    }
}
