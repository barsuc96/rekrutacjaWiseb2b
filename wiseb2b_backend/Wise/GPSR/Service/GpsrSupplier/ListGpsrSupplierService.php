<?php

namespace Wise\GPSR\Service\GpsrSupplier;

use Wise\Core\Service\AbstractListService;
use Wise\GPSR\Domain\GpsrSupplier\GpsrSupplierRepositoryInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\ListGpsrSupplierServiceInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\GpsrSupplierAdditionalFieldsServiceInterface;

/**
 * Serwis zwracający informacje o dostawcach (lista)
 */
class ListGpsrSupplierService extends AbstractListService implements ListGpsrSupplierServiceInterface
{
    /**
     * Czy umożliwiać wyszukiwanie za pomocą searchKeyword
     */
    protected const ENABLE_SEARCH_KEYWORD = true;

    public function __construct(
        private readonly GpsrSupplierRepositoryInterface $repository,
        private readonly GpsrSupplierAdditionalFieldsServiceInterface $additionalFieldsService
    ){
        parent::__construct($repository, $additionalFieldsService);
    }

    /**
     * Lista pól, które mają być obsługiwane w filtrowaniu z pola searchKeyword
     * @return string[]
     */
    protected function getDefaultSearchFields(): array
    {
        return [
            'symbol',
        ];
    }
}
