<?php

namespace Wise\GPSR\Service\GpsrSupplier;

use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractModifyService;
use Wise\GPSR\Domain\GpsrSupplier\GpsrSupplierRepositoryInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\ModifyGpsrSupplierServiceInterface;

/**
 * Serwis modyfikujący dostawców
 */
class ModifyGpsrSupplierService extends AbstractModifyService implements ModifyGpsrSupplierServiceInterface
{
    public function __construct(
        private readonly GpsrSupplierRepositoryInterface $repository,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ){
        parent::__construct($repository, $persistenceShareMethodsHelper);
    }

    /**
     * Przygotowanie danych przed połączeniem ich z encją za pomocą Merge Service
     * @param array|null $data
     * @param AbstractEntity $entity
     * @return void
     */
    protected function prepareDataBeforeMergeData(?array &$data, AbstractEntity $entity): void
    {

    }
}
