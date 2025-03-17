<?php

namespace Wise\GPSR\Service\GpsrSupplier;

use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractAddService;
use Wise\GPSR\Domain\GpsrSupplier\Factory\GpsrSupplierFactory;
use Wise\GPSR\Domain\GpsrSupplier\GpsrSupplierRepositoryInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\AddGpsrSupplierServiceInterface;

/**
 * Serwis dodający dostawców
 */
class AddGpsrSupplierService extends AbstractAddService implements AddGpsrSupplierServiceInterface
{
    public function __construct(
        private readonly GpsrSupplierRepositoryInterface $repository,
        private readonly GpsrSupplierFactory $entityFactory,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ){
        parent::__construct($repository, $entityFactory, $persistenceShareMethodsHelper);
    }

    /**
     * Umożliwia przygotowanie danych do utworzenia encji w fabryce.
     * @param array|null $data
     * @return array
     */
    protected function prepareDataBeforeCreateEntity(?array &$data): array
    {
        return $data;
    }
}
