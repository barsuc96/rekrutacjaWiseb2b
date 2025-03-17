<?php

declare(strict_types=1);

namespace Wise\Security;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class WiseSecurityConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(WiseSecurityExtension::getExtensionAlias());

        $this->addRecaptchaConfiguration($treeBuilder);

        return $treeBuilder;
    }

    protected function addRecaptchaConfiguration($treeBuilder): void
    {
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('allow_recaptcha')->end()
                ->scalarNode('recaptcha_secret')->end()
            ->end();
    }

}
