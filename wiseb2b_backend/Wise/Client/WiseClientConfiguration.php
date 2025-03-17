<?php

declare(strict_types=1);

namespace Wise\Client;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class WiseClientConfiguration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(WiseClientExtension::getExtensionAlias());

        $this->addClientStatusConfiguration($treeBuilder);
        $this->addUniqueTaxNumberConfiguration($treeBuilder);
        $this->addDefaultClientGroupConfiguration($treeBuilder);
        $this->addValidatePhoneNumberConfiguration($treeBuilder);

        return $treeBuilder;
    }

    /**
     * Konfiguracja statusów klienta
     */
    private function addClientStatusConfiguration($treeBuilder): void
    {
        $treeBuilder->getRootNode()
            ->children()
                ->integerNode('default_client_status')->end()
                ->integerNode('client_status_accepted')->end()
                ->arrayNode('client_statuses_to_get_client_api_data')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('client_status')
                ->arrayPrototype()
                ->children()
                    ->scalarNode('status_number')->end()
                ->end()
            ->end();
    }

    private function addUniqueTaxNumberConfiguration($treeBuilder): void
    {
        $treeBuilder->getRootNode()
            ->children()
                ->booleanNode('verify_client_unique_tax_number')->end()
            ->end();
    }

    private function addValidatePhoneNumberConfiguration($treeBuilder): void
    {
        $treeBuilder->getRootNode()
            ->children()
                ->booleanNode('validate_phone_number')->end()
            ->end();
    }

    private function addDefaultClientGroupConfiguration($treeBuilder): void
    {
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('default_register_client_group')->end()
            ->end();
    }
}
