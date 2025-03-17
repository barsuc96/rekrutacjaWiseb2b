<?php

declare(strict_types=1);

namespace Wise\Core\Service;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Stopwatch\Stopwatch;
use Wise\Core\Service\Interfaces\ConfigServiceInterface;
use Wise\MultiStore\Domain\Store\Service\Interfaces\StoreServiceInterface;
use Wise\MultiStore\Service\CurrentStoreService;
use Wise\MultiStore\WiseMultiStoreExtension;

/**
 * Serwis zwraca konfigurację aplikacji
 */
class ConfigService implements ConfigServiceInterface
{
    const CONFIG_FOR_CURRENT_STORE_KEY = 'store_configuration';

    public function __construct(
        private readonly ContainerBagInterface $configParams,
        private readonly CurrentStoreService $currentStoreService,
        private readonly StoreServiceInterface $storeService,
        private RequestStack $requestStack,
        private readonly Stopwatch $stopwatch
    ){}

    /**
     * Zwraca konfiguracje na podstawie klucza
     * z uwzględnieniem nadpisania konfiguracji z MultiStore
     * @param string $key
     * @param bool $returnKeyWhenNotExistsConfiguration
     * @param bool $returnOnlyCurrentStoreConfigWithoutRegularConfig Parametr ten pozwala na zwrócenie tylko konfiguracji sklepu z multistore (obejście we wdrożeniu aby można było nadpisać konfigurację, która jest tablicowa)
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function get(mixed $key, bool $returnKeyWhenNotExistsConfiguration = false, bool $returnOnlyCurrentStoreConfigWithoutRegularConfig = false): mixed
    {
        $this->stopwatch->start('ConfigService::get');
        $currentStore = $this->currentStoreService->getCurrentStore();

        $currentStoreConfig = [];
        if ($currentStore !== null) {
            try {
                $currentStoreConfig = $this->configParams->get(WiseMultiStoreExtension::ALIAS);
                if (isset($currentStoreConfig['store_config_overrides']) &&
                    isset($currentStoreConfig['store_config_overrides'][$currentStore->getSymbol()]) &&
                    isset($currentStoreConfig['store_config_overrides'][$currentStore->getSymbol()][$key])) {
                    $currentStoreConfig = $currentStoreConfig['store_config_overrides'][$currentStore->getSymbol()][$key];
                }

                // Może być tak, że klucz jest podany z małych liter
                if (is_array($currentStoreConfig) &&
                    isset($currentStoreConfig['store_config_overrides'][$currentStore->getSymbol()][strtolower($key)])) {
                    $currentStoreConfig = $currentStoreConfig['store_config_overrides'][$currentStore->getSymbol()][strtolower($key)];
                }
            } catch (\Exception $e) {
                $currentStoreConfig = [];
            }
        }

        if($returnOnlyCurrentStoreConfigWithoutRegularConfig){
            $this->stopwatch->stop('ConfigService::get');
            return $currentStoreConfig;
        }

        try{
            $regularConfig = $this->configParams->get($key);
        } catch (\Exception $e) {
            $regularConfig = null;
        }

        // Jeśli nie udało się znaleźć w config.yaml to spróbujmy jeszcze raz z małymi literami
        if($regularConfig === null){
            try{
                $regularConfig = $this->configParams->get(strtolower($key));
            } catch (\Exception $e) {
                $regularConfig = null;
            }
        }


        if (is_string($currentStoreConfig))
        {
            $this->stopwatch->stop('ConfigService::get');
            return $currentStoreConfig;
        }

        /**
         * nadpisywanie konfiguracji podstawowej przez konfigurację sklepu z multistore
         */

        if (is_array($regularConfig) && is_array($currentStoreConfig))
        {
            $this->stopwatch->start('ConfigService::get::array_replace_recursive');
            $regularConfig = array_replace_recursive($regularConfig, $currentStoreConfig);
            $this->stopwatch->stop('ConfigService::get::array_replace_recursive');
        }

        $this->stopwatch->stop('ConfigService::get');

        if($regularConfig === null && $returnKeyWhenNotExistsConfiguration){
            return $key;
        }

        return $regularConfig;
    }

}
