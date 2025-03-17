<?php

declare(strict_types=1);

namespace Wise\Service;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wise\Core\WiseBaseExtension;

class WiseServiceExtension extends WiseBaseExtension
{
    const ALIAS = 'wise_service';

    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new WiseServiceConfiguration();
    }
}
