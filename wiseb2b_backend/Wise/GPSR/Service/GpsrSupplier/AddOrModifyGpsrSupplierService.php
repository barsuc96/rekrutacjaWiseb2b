<?php

namespace Wise\GPSR\Service\GpsrSupplier;

use Wise\Core\Service\AbstractAddOrModifyService;
use Wise\GPSR\Domain\GpsrSupplier\GpsrSupplierRepositoryInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\AddOrModifyGpsrSupplierServiceInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\AddGpsrSupplierServiceInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\ModifyGpsrSupplierServiceInterface;

/**
 * Serwis dodający lub modyfikujący dostawców
 */
class AddOrModifyGpsrSupplierService extends AbstractAddOrModifyService implements AddOrModifyGpsrSupplierServiceInterface
{
    public function __construct(
        private readonly GpsrSupplierRepositoryInterface $repository,
        private readonly AddGpsrSupplierServiceInterface $addService,
        private readonly ModifyGpsrSupplierServiceInterface $modifyService,
    ) {
        parent::__construct($repository, $addService, $modifyService);
    }
}
