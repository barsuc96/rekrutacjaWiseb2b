<?php

namespace Wise\GPSR\Service\GpsrSupplier;

use Wise\Core\Service\AbstractDetailsService;
use Wise\GPSR\Domain\GpsrSupplier\GpsrSupplierRepositoryInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\GetGpsrSupplierDetailsServiceInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\GpsrSupplierAdditionalFieldsServiceInterface;

/**
 * Serwis zwracający informacje o dostawcach (pojedyńczy dostawca)
 */
class GetGpsrSupplierDetailsService extends AbstractDetailsService implements GetGpsrSupplierDetailsServiceInterface
{
    public function __construct(
        private readonly GpsrSupplierRepositoryInterface $repository,
        private readonly GpsrSupplierAdditionalFieldsServiceInterface $additionalFieldsService
    ){
        parent::__construct($repository, $additionalFieldsService);
    }

}
