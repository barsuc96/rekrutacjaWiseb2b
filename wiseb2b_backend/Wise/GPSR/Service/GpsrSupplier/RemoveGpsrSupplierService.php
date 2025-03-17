<?php

namespace Wise\GPSR\Service\GpsrSupplier;

use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractRemoveService;
use Wise\GPSR\Domain\GpsrSupplier\Event\GpsrSupplierAfterRemoveEvent;
use Wise\GPSR\Domain\GpsrSupplier\Event\GpsrSupplierBeforeRemoveEvent;
use Wise\GPSR\Domain\GpsrSupplier\GpsrSupplierRepositoryInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\ListGpsrSupplierServiceInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\RemoveGpsrSupplierServiceInterface;

/**
 * Serwis usuwający dostawcę
 */
class RemoveGpsrSupplierService extends AbstractRemoveService implements RemoveGpsrSupplierServiceInterface
{
    protected const BEFORE_REMOVE_EVENT_NAME = GpsrSupplierBeforeRemoveEvent::class;
    protected const AFTER_REMOVE_EVENT_NAME = GpsrSupplierAfterRemoveEvent::class;

    public function __construct(
        private readonly GpsrSupplierRepositoryInterface $supplierRepository,
        private readonly ListGpsrSupplierServiceInterface $listSupplierService,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ) {
        parent::__construct($supplierRepository, $listSupplierService, $persistenceShareMethodsHelper);
    }
}
