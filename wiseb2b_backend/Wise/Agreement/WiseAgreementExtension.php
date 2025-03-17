<?php

namespace Wise\Agreement;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wise\Core\WiseBaseExtension;

class WiseAgreementExtension extends WiseBaseExtension
{
    const ALIAS = 'wise_agreement';

    public function getConfiguration(array $config, ContainerBuilder $container): WiseAgreementConfiguration
    {
        return new WiseAgreementConfiguration();
    }
}
