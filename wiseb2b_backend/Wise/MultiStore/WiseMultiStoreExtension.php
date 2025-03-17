<?php

declare(strict_types=1);

namespace Wise\MultiStore;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wise\Core\WiseBaseExtension;

class WiseMultiStoreExtension extends WiseBaseExtension
{
    const ALIAS = 'wise_multi_store';
    const CURRENT_STORE_SYMBOL = 'current_store_symbol';

    public function getConfiguration(array $config, ContainerBuilder $container): WiseMultiStoreConfiguration
    {
        return new WiseMultiStoreConfiguration();
    }
}
