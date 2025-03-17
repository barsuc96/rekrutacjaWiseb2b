<?php

declare(strict_types=1);

namespace Wise\MultiStore\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Wise\MultiStore\Service\Interfaces\CurrentStoreServiceInterface;
use Wise\MultiStore\Service\Interfaces\TranslateValueByCurrentStoreServiceInterface;
use Wise\MultiStore\WiseMultiStoreExtension;

/**
 * Serwis translacji wartości na podstawie aktualnego sklepu.
 * Pozwala zwrócić klucz specjalnie przygotowany dla danego sklepu.
 * Jeśli klucz nie istnieje dla danego sklepu, zwraca klucz podany w parametrze.
 */
class TranslateValueByCurrentStoreService implements TranslateValueByCurrentStoreServiceInterface
{
    public function __construct(
        private readonly CurrentStoreServiceInterface $currentStoreService,
        private readonly ContainerBagInterface $configParams,
    ){}

    /**
     * @param string $moduleName - nazwa modułu, dla którego chcemy pobrać tłumaczenie
     * @param string $key - klucz, dla którego chcemy pobrać tłumaczenie
     * @param int|null $storyId - id sklepu, dla którego chcemy pobrać tłumaczenie (null pobiera aktualny sklep)
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(string $moduleName, string $key, ?int $storyId = null): string
    {
        // Sprawdzam, czy istnieje konfiguracja dla sklepu
        $currentStore = $this->currentStoreService->getCurrentStore();

        if($storyId !== null){
            $currentStore = $this->currentStoreService->getStoreById($storyId);
        }

        if ($currentStore !== null) {
            try {
                $currentStoreConfig = $this->configParams->get(WiseMultiStoreExtension::ALIAS);
                if (isset($currentStoreConfig['translations'][$currentStore->getSymbol()]['modules'][$moduleName][$key])){
                    return $currentStoreConfig['translations'][$currentStore->getSymbol()]['modules'][$moduleName][$key];
                }
            } catch (\Exception $e) {}
        }

        return $key;
    }
}
