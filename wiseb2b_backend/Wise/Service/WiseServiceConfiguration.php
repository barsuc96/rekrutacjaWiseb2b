<?php

declare(strict_types=1);

namespace Wise\Service;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class WiseServiceConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(WiseServiceExtension::getExtensionAlias());

        $this->addDefaulDriverNameConfiguration($treeBuilder);
        $this->addDefaulDriverPaymentMethodsConfiguration($treeBuilder);

        return $treeBuilder;
    }

    protected function addDefaulDriverNameConfiguration(TreeBuilder $treeBuilder)
    {
        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('service_driver')
                    ->children()
                        ->scalarNode('default_driver_name')->end()
                    ->end()
                ->end()
            ->end();
    }

    protected function addDefaulDriverPaymentMethodsConfiguration(TreeBuilder $treeBuilder)
    {
        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('service_cost_provider')
                    ->children()
                        ->scalarNode('default_delivery_method_id')->end()
                        ->scalarNode('default_payment_method_id')->end()
                    ->end()
                ->end()
            ->end();
    }

}
