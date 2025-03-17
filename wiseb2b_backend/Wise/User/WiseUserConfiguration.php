<?php

declare(strict_types=1);

namespace Wise\User;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class WiseUserConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(WiseUserExtension::getExtensionAlias());

        $this->addPostUserServiceConfiguration($treeBuilder);
        $this->getCountryCodesServiceConfiguration($treeBuilder);
        $this->addUserTermsServiceConfiguration($treeBuilder);
        $this->addContactOwnerEmailConfiguration($treeBuilder);

        return $treeBuilder;
    }

    protected function addPostUserServiceConfiguration($treeBuilder): void
    {
        $treeBuilder->getRootNode()
                        ->children()
                            ->arrayNode('post_user_service')
                            ->children()
                                ->arrayNode('welcome_msg')
                                    ->children()
                                    ->scalarNode('template')->end()
                                ->end()
                            ->end()
                        ->end();
    }

    protected function addUserTermsServiceConfiguration($treeBuilder): void
    {
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('section_agreement_symbol')->end()
                ->arrayNode('get_terms_agreements_to_show')
                    ->useAttributeAsKey('type')
                        ->arrayPrototype()
                        ->children()
                            ->scalarNode('article_symbol')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function getCountryCodesServiceConfiguration($treeBuilder) {
        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('country_codes_service')
                    ->children()
                        ->arrayNode('available_countries')
                            ->scalarPrototype()->end()
                            ->defaultValue([])
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Konfiguracja dotycząca maila do właściciela sklepu
     */
    protected function addContactOwnerEmailConfiguration(TreeBuilder $treeBuilder): void
    {
        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('get_users_profile_service')
                    ->children()
                        ->scalarNode('contact_owner_email')->end()
                    ->end()
                ->end()
            ->end();
    }
}
