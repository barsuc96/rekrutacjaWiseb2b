<?php

declare(strict_types=1);

namespace Wise\MultiStore\Domain\Store\Service;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Wise\MultiStore\Domain\Store\Service\Interfaces\StoreConfigurationHelperInterface;
use Wise\MultiStore\WiseMultiStoreExtension;

/**
 * Klasa pomocnicza do obsługi konfiguracji sklepów
 */
class StoreConfigurationHelper implements StoreConfigurationHelperInterface
{
    public function __construct(
        private readonly ContainerBagInterface $configParams,
        private RequestStack $requestStack,
    ){}


    /**
     * Metoda wczytująca dane z plików konfiguracyjnych sklepów
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getStoresTranslations(): array
    {
        $config = $this->configParams->get(WiseMultiStoreExtension::ALIAS);

        return $config['translations'];
    }
}
