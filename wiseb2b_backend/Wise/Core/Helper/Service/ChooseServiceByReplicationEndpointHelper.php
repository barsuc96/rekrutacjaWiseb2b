<?php

declare(strict_types=1);


namespace Wise\Core\Helper\Service;

use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Wise\Core\ApiAdmin\Service\AbstractDeleteAdminApiService;
use Wise\Core\ApiAdmin\Service\AbstractDeleteService;
use Wise\Core\ApiAdmin\Service\AbstractGetDetailsAdminApiService;
use Wise\Core\ApiAdmin\Service\AbstractGetListAdminApiService;
use Wise\Core\ApiAdmin\Service\AbstractGetService;
use Wise\Core\ApiAdmin\Service\AbstractPutAdminApiService;
use Wise\Core\ApiAdmin\Service\AbstractPutService;

/**
 * Klasa do wybierania serwisu na podstawie danych z bazy dla endpointów replikacji
 */
class ChooseServiceByReplicationEndpointHelper
{
    public function __construct(
        protected readonly ContainerInterface $container,
    ) {
    }

    public function chooseServiceByClass(string $serviceClass):
    AbstractGetService|AbstractPutService|AbstractDeleteService|AbstractPutAdminApiService|AbstractGetDetailsAdminApiService|AbstractGetListAdminApiService|AbstractDeleteAdminApiService {
        if (!$this->container->has($serviceClass)) {
            throw new Exception('Service not found');
        }

        $serviceObject = $this->container->get($serviceClass);
        if (
            $serviceObject instanceof AbstractDeleteService ||
            $serviceObject instanceof AbstractGetService ||
            $serviceObject instanceof AbstractPutService ||
            $serviceObject instanceof AbstractPutAdminApiService ||
            $serviceObject instanceof AbstractGetDetailsAdminApiService ||
            $serviceObject instanceof AbstractGetListAdminApiService ||
            $serviceObject instanceof AbstractDeleteAdminApiService
        ) {
            return $serviceObject;
        }

        throw new Exception('Invalid service class');
    }
}
