<?php

declare(strict_types=1);


namespace Wise\Core\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wise\Core\ApiAdmin\Service\AbstractDeleteAdminApiService;
use Wise\Core\ApiAdmin\Service\AbstractDeleteService;
use Wise\Core\ApiAdmin\Service\AbstractGetDetailsAdminApiService;
use Wise\Core\ApiAdmin\Service\AbstractGetListAdminApiService;
use Wise\Core\ApiAdmin\Service\AbstractGetService;
use Wise\Core\ApiAdmin\Service\AbstractPutAdminApiService;
use Wise\Core\ApiAdmin\Service\AbstractPutService;

/**
 * Compiler pass który ustawia wszystkie serwisy dziedziczące po AbstractDeleteService, AbstractGetService
 * i AbstractPutService jako publiczne i dostępne z kontenera. Dzięki temu można je podpinać dynamicznie do serwisów.
 */
class MakeAdminApiServicesPublicPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $serviceDefinition) {
            try {
                if (is_subclass_of($serviceDefinition->getClass(), AbstractPutService::class)) {
                    $serviceDefinition->setPublic(true);
                } elseif (is_subclass_of($serviceDefinition->getClass(), AbstractDeleteService::class)) {
                    $serviceDefinition->setPublic(true);
                } elseif (is_subclass_of($serviceDefinition->getClass(), AbstractGetService::class)) {
                    $serviceDefinition->setPublic(true);
                }elseif (is_subclass_of($serviceDefinition->getClass(), AbstractPutAdminApiService::class)) {
                    $serviceDefinition->setPublic(true);
                }elseif (is_subclass_of($serviceDefinition->getClass(), AbstractGetDetailsAdminApiService::class)) {
                    $serviceDefinition->setPublic(true);
                }elseif (is_subclass_of($serviceDefinition->getClass(), AbstractGetListAdminApiService::class)) {
                    $serviceDefinition->setPublic(true);
                }elseif (is_subclass_of($serviceDefinition->getClass(), AbstractDeleteAdminApiService::class)) {
                    $serviceDefinition->setPublic(true);
                }
            } catch (\Throwable $e) {
                continue;
            }
        }
    }
}
