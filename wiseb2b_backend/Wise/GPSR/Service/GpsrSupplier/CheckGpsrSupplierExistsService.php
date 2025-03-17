<?php

namespace Wise\GPSR\Service\GpsrSupplier;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonDetailsParams;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\AddGpsrSupplierServiceInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\CheckGpsrSupplierExistsServiceInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\GetGpsrSupplierDetailsServiceInterface;

/**
 * Serwis weryfikuje czy istnieje dostawca o danym symbolu, a jeśli nie to dodaje go pustego
 */
class CheckGpsrSupplierExistsService implements CheckGpsrSupplierExistsServiceInterface
{
    public function __construct(
        private readonly GetGpsrSupplierDetailsServiceInterface $getGpsrSupplierDetailsService,
        private readonly AddGpsrSupplierServiceInterface $addGpsrSupplierService
    ){}

    public function __invoke(?string $symbol): void
    {
        if(empty($symbol)){
            return;
        }

        // Pobiera dostawce
        $supplier = $this->getSupplier($symbol);

        // Jeśli nie istnieje to dodaje pustego dostawcę
        if(empty($supplier)){
            $this->addEmptySupplierWithSymbol($symbol);
        }
    }


    /**
     * Zwraca szczegóły dostawcy
     * @param string $symbol
     * @return array
     */
    public function getSupplier(string $symbol): array
    {
        $params = new CommonDetailsParams();
        $params
            ->setFilters([
                new QueryFilter('symbol', $symbol)
            ])
            ->setFields(['id' => 'id'])
            ->setExecuteExceptionWhenEntityNotExists(false);

        return ($this->getGpsrSupplierDetailsService)(params: $params)->read();
    }

    /**
     * Dodaje pustego dostawcę z symbolem
     * @param string $symbol
     * @return void
     */
    protected function addEmptySupplierWithSymbol(string $symbol): void
    {
        $params = new CommonModifyParams();
        $params
            ->writeAssociativeArray([
                'symbol' => $symbol,
                'isActive' => false
            ]);

        ($this->addGpsrSupplierService)($params);
    }
}
