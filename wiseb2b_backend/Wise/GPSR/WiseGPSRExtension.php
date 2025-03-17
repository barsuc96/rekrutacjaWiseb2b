<?php

namespace Wise\GPSR;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wise\Core\WiseBaseExtension;

class WiseGPSRExtension extends WiseBaseExtension
{
    public const ALIAS = 'wise_gpsr';

    public function getConfiguration(array $config, ContainerBuilder $container): WiseGPSRConfiguration
    {
        return new WiseGPSRConfiguration();
    }
}
