<?php

declare(strict_types=1);

namespace Wise\Receiver;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wise\Core\WiseBaseExtension;

class WiseReceiverExtension extends WiseBaseExtension
{
    const ALIAS = 'wise_receiver';

    public function getConfiguration(array $config, ContainerBuilder $container): WiseReceiverConfiguration
    {
        return new WiseReceiverConfiguration();
    }
}
