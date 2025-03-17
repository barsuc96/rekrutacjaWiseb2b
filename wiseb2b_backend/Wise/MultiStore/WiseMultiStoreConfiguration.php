<?php

declare(strict_types=1);

namespace Wise\MultiStore;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class WiseMultiStoreConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(WiseMultiStoreExtension::getExtensionAlias());

        $this->addApiClientToStoreConfiguration($treeBuilder);
        $this->addDefaultStoreSymbolConfiguration($treeBuilder);
        $this->addStoresConfiguration($treeBuilder);
        $this->addStoreConfigOverridesConfiguration($treeBuilder);
        $this->addStoresTranslationsConfiguration($treeBuilder);

        return $treeBuilder;
    }

    protected function addApiClientToStoreConfiguration($treeBuilder): void
    {
        $treeBuilder->getRootNode()
                        ->children()
                            ->arrayNode('api_client_to_store')
                            ->prototype('array')
                            ->children()
                                ->scalarNode('store_symbol')->end()
                            ->end()
                        ->end();
    }

    protected function addDefaultStoreSymbolConfiguration($treeBuilder): void
    {
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('default_store_symbol')->end()
            ->end();
    }

    protected function addStoresConfiguration(TreeBuilder $treeBuilder): void
    {
        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('stores')
                ->prototype('array')
                ->children()
                    ->scalarNode('id')->end()
                    ->scalarNode('symbol')->end()
                    ->scalarNode('name')->end()
                ->end()
            ->end();
    }

    protected function addStoreConfigOverridesConfiguration(TreeBuilder $treeBuilder): void
    {
        $treeBuilder->getRootNode()
            ->children()
                ->variableNode('store_config_overrides')
                ->end()
            ->end();
    }

    protected function addStoresTranslationsConfiguration(TreeBuilder $treeBuilder): void
    {
        $treeBuilder->getRootNode()
            ->children()
                ->variableNode('translations')
                ->end()
            ->end();
    }
}
