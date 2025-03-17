<?php

namespace Wise\GPSR;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class WiseGPSRConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(WiseGPSRExtension::getExtensionAlias());
        $this->addGPSRConfiguration($treeBuilder);

        return $treeBuilder;
    }



    /**
     * Konfiguracja GPSR
     * @param TreeBuilder $treeBuilder
     * @return void
     */
    protected function addGPSRConfiguration(TreeBuilder $treeBuilder)
    {
        $treeBuilder
            ->getRootNode()
                ->children()
                    ->scalarNode('gpsr_liability_attribute_symbol')->end()
                    ->booleanNode('create_empty_supplier_on_new_attribute')->end()
                ->end()
            ->end();
    }

}
