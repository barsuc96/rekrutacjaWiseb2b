<?php

declare(strict_types=1);

namespace Wise\User;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wise\Core\WiseBaseExtension;

class WiseUserExtension extends WiseBaseExtension
{
    const ALIAS = 'wise_user';

    public function getConfiguration(array $config, ContainerBuilder $container): WiseUserConfiguration
    {
        return new WiseUserConfiguration();
    }
}