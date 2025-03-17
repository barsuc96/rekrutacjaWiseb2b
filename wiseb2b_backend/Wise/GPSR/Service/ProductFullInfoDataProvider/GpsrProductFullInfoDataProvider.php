<?php

namespace Wise\GPSR\Service\ProductFullInfoDataProvider;

use Wise\Core\Helper\Array\ArrayHelper;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\Interfaces\ConfigServiceInterface;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\GPSR\Service\ProductFullInfoDataProvider\Payload\GpsrSupplierInfo;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\ListGpsrSupplierServiceInterface;
use Wise\GPSR\WiseGPSRExtension;
use Wise\I18n\Service\Country\Interfaces\CountryHelperInterface;
use Wise\Product\Service\Attribute\AttributeInfo;
use Wise\SearchProduct\Service\ProductFullInfo\ProductFullInfo;
use Wise\SearchProduct\Service\ProductFullInfo\ProductFullInfoDataProvider\Abstract\AbstractProductInfosScopeProvider;
use Wise\SearchProduct\Service\ProductFullInfo\ProductFullInfoDataProvider\ProductFullInfosParams;
use Wise\SearchProduct\Service\ProductFullInfo\ProductFullInfoDataProvider\ProductFullInfosProviderInterface;

/**
 * Provider zwracający do ProductFullInfo informacje o dostawcy na potrzebe GPSR
 * UWAGA: Zwróć uwagę, że w przypadku braku dostawcy, nie dodajemy informacji o dostawcy do ProductFullInfo
 */
class GpsrProductFullInfoDataProvider extends AbstractProductInfosScopeProvider implements ProductFullInfosProviderInterface
{
    /**
     * Nazwa scope'a
     */
    protected const SCOPE = null;

    /**
     * Klasa payload'u
     */
    protected const PAYLOAD_CLASS = GpsrSupplierInfo::class;

    public function __construct(
        private readonly ConfigServiceInterface $configService,
        private readonly ListGpsrSupplierServiceInterface $listSupplierService,
        private readonly CountryHelperInterface $countryHelper,
        private readonly LocaleServiceInterface $localeService
    ){}

    public function __invoke(array $productFullInfos, ProductFullInfosParams $params, array &$cache): void
    {
        $configProduct = $this->configService->get(WiseGPSRExtension::getExtensionAlias());
        $gpsrAttributeSymbol = $configProduct['gpsr_liability_attribute_symbol'];

        // Przygotowanie danych
        $listOfSuppliersSymbols = $this->prepareListOfSuppliersSymbols($productFullInfos, $gpsrAttributeSymbol);
        $cache['suppliersSymbols'] = $listOfSuppliersSymbols;

        $listSuppliersData = $this->getSuppliersData($listOfSuppliersSymbols);
        $cache['suppliersData'] = $listSuppliersData;

        // Uzupełnienie danych
        foreach ($productFullInfos as $productFullInfo){
            $this->addGpsrProductFullInfo($productFullInfo, $params, $cache, $gpsrAttributeSymbol);
        }
    }

    /**
     * Uzupełnia dane produktu o informacje z GPSR
     * @param ProductFullInfo $productFullInfo
     * @param ProductFullInfosParams $params
     * @param array $cache
     * @param string $gpsrAttributeSymbol
     * @return void
     */
    protected function addGpsrProductFullInfo(ProductFullInfo $productFullInfo, ProductFullInfosParams $params, array $cache, string $gpsrAttributeSymbol): void
    {
        $supplierSymbol = $cache['suppliersSymbols'][$productFullInfo->getId()] ?? null;
        if($supplierSymbol !== null){
            $supplierData = $cache['suppliersData'][$supplierSymbol] ?? null;
            if($supplierData !== null){

                // Dodanie informacji o dostawcy
                // Zwróć uwagę, że dodajemy tylko wtedy, gdy dostawca istnieje
                $productFullInfo->set(GpsrSupplierInfo::fromArray($supplierData));
            }
        }
    }

    /**
     * Zwraca listę symboli dostawców
     * @param array $productFullInfos
     * @param string $gpsrAttributeSymbol
     * @return array
     */
    protected function prepareListOfSuppliersSymbols(array $productFullInfos, string $gpsrAttributeSymbol): array
    {
        $suppliersSymbols = [];

        /** @var ProductFullInfo $productFullInfo */
        foreach ($productFullInfos as $productFullInfo){
            if(empty($productFullInfo->getAttributes())){
                continue;
            }

            /** @var AttributeInfo $attribute */
            foreach ($productFullInfo->getAttributes() as $attribute){
                if($attribute->getSymbol() === $gpsrAttributeSymbol){
                    if(empty($attribute->getValues())){
                        continue;
                    }

                    foreach ($attribute->getValues() as $value){
                        if($value['language'] !== 'pl'){
                            continue;
                        }

                        $suppliersSymbols[$productFullInfo->getId()] = $value['translation'];
                    }

                }
            }
        }

        return $suppliersSymbols;
    }

    /**
     * Zwraca dane o dostawcach
     * @param array $listOfSuppliersSymbols
     * @return array
     */
    protected function getSuppliersData(array $listOfSuppliersSymbols): array
    {
        if(empty($listOfSuppliersSymbols)){
            return [];
        }

        $params = new CommonListParams();
        $params
            ->setFilters([
                new QueryFilter('symbol', array_unique(array_values($listOfSuppliersSymbols)))
            ])
            ->setFields(['id' => 'id', 'address' => 'address', 'symbol' => 'symbol', 'taxNumber' => 'taxNumber', 'email' => 'email', 'phone' => 'phone', 'registeredTradeName' => 'registeredTradeName']);

        $suppliers = ($this->listSupplierService)($params)->read();
        $this->fillCountryForSuppliers($suppliers);

        $suppliers = ArrayHelper::rearrangeKeysWithValuesUsingReferences($suppliers, 'symbol');

        return $suppliers;
    }

    /**
     * Uzupełnia nazwy kraju
     * @param array|null $suppliers
     * @return void
     */
    protected function fillCountryForSuppliers(?array &$suppliers): void
    {
        $countryCodes = [];

        foreach ($suppliers as $supplier){
            if(!empty($supplier['address']['countryCode'])){
                $countryCodes[] = strtoupper($supplier['address']['countryCode']);
            }
        }

        if(empty($countryCodes)){
            return;
        }

        $countryCodes = array_unique($countryCodes);
        $countryNames = $this->countryHelper->getCountryNamesByIso($countryCodes, $this->localeService->getCurrentLanguage());

        foreach ($suppliers as &$supplier){
            $countryCode = $supplier['address']['countryCode'] ?? null;
            $supplier['address']['country'] = null;
            if($countryCode !== null){
                $supplier['address']['country'] = $countryNames[strtoupper($countryCode)] ?? null;
            }
        }
    }
}
