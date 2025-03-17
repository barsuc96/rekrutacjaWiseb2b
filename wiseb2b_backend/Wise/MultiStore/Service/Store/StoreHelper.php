<?php

declare(strict_types=1);

namespace Wise\MultiStore\Service\Store;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Wise\Core\DataTransformer\CommonDataTransformer;
use Wise\Core\Service\AbstractHelper;
use Wise\MultiStore\Domain\Store\Service\Interfaces\StoreServiceInterface;
use Wise\MultiStore\Service\Interfaces\CurrentStoreServiceInterface;
use Wise\MultiStore\Service\Store\Interfaces\StoreHelperInterface;
use Wise\MultiStore\WiseMultiStoreExtension;

class StoreHelper extends AbstractHelper implements StoreHelperInterface
{
    public function __construct(
        private readonly StoreServiceInterface $storeService,
        private readonly CurrentStoreServiceInterface $currentStoreService,
        private readonly ContainerBagInterface $configParams,
        private readonly Stopwatch $stopwatch
    ){
        parent::__construct($storeService);
    }

    /**
     * Zwraca informacje o sklepie dla konkretnego klienta.
     * Metoda również pozwala zweryfikować, czy klient jest przypisany do danego sklepu
     * @param int $clientId
     * @return array|null
     */
    public function getStoresByClientId(int $clientId): ?array
    {
        $store = $this->storeService->getStoresByClientId($clientId);

        if($store === null){
            return null;
        }

        return CommonDataTransformer::transformToArray($store);
    }


    /**
     * Zwraca identyfikator encji, jeśli istnieje
     * @param array $data
     * @param bool $executeNotFoundException
     * @return int|null
     */
    public function getIdIfExistByDataExternal(array $data, bool $executeNotFoundException = true): ?int
    {
        $id = $data['storeId'] ?? null;
        $idExternal = null;

        return $this->storeService->getIdIfExist($id, $idExternal, $executeNotFoundException);
    }

    /**
     * Zwraca identyfikator encji na podstawie date, jeśli znajdują się tam zewnętrzne klucze
     * @param array $data
     * @param bool $executeNotFoundException
     * @return void
     */
    public function prepareExternalData(array &$data, bool $executeNotFoundException = true): void
    {
        // Sprawdzam, czy istnieją pola
        if(!isset($data['storeId'])){
            return;
        }

        // Pobieram identyfikator
        $id = $data['storeId'] ?? null;
        $idExternal = null;

        $data['storeId'] = $this->storeService->getIdIfExist($id, $idExternal, $executeNotFoundException);
    }

    /**
     * Weryfikacja, czy dany artykuł może być wyświetlany przez sklep
     * @param string $articleSymbol
     * @return bool
     */
    public function verifyArticleCanViewForStore(string $articleSymbol): bool
    {
        return false;
    }

    /**
     * Weryfikacja, czy dany artykuł może być wyświetlany przez sklep
     * @param string $sectionSymbol
     * @return bool
     */
    public function verifySectionCanViewForStore(string $sectionSymbol): bool
    {
        return false;
    }

    /**
     * Zwraca domyślny symbol sklepu z konfiguracji
     * @return string
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getDefaultStoreSymbolFromConfiguration(): string
    {
        $config = $this->configParams->get(WiseMultiStoreExtension::getExtensionAlias());

        return $config['default_store_symbol'];
    }
}
