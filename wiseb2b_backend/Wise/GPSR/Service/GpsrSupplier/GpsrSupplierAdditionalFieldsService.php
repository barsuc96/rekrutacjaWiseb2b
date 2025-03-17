<?php

namespace Wise\GPSR\Service\GpsrSupplier;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Wise\Core\Service\AbstractAdditionalFieldsService;
use Wise\GPSR\Service\GpsrSupplier\DataProvider\SupplierProviderInterface;
use Wise\GPSR\Service\GpsrSupplier\Interfaces\GpsrSupplierAdditionalFieldsServiceInterface;

/**
 * Serwis pozwala zwrócić dodatkowe pola dla dostawcy obsłużone za pomocą provider'ów
 */
class GpsrSupplierAdditionalFieldsService extends AbstractAdditionalFieldsService implements GpsrSupplierAdditionalFieldsServiceInterface
{
    protected const PROVIDER_INTERFACE = SupplierProviderInterface::class;

    public function __construct(
        #[TaggedIterator('details_provider.supplier')] iterable $providers
    ){
        parent::__construct($providers);
    }
}
