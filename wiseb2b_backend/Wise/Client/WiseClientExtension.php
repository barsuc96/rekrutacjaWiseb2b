<?php

declare(strict_types=1);

namespace Wise\Client;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wise\Core\WiseBaseExtension;

class WiseClientExtension extends WiseBaseExtension
{
    const ALIAS = 'wise_client';

    public function getConfiguration(array $config, ContainerBuilder $container): WiseClientConfiguration
    {
        return new WiseClientConfiguration();
    }
}
