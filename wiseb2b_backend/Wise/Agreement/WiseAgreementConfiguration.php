<?php

namespace Wise\Agreement;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class WiseAgreementConfiguration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(WiseAgreementExtension::getExtensionAlias());

        return $treeBuilder;
    }
}
