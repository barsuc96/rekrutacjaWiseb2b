<?php

declare(strict_types=1);

namespace Wise\Receiver;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class WiseReceiverConfiguration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(WiseReceiverExtension::getExtensionAlias());

        $this->addConfigurationAboutAddOrModifyReceiver($treeBuilder);

        return $treeBuilder;
    }

    /**
     * @param $treeBuilder
     * @return void
     */
    protected function addConfigurationAboutAddOrModifyReceiver($treeBuilder): void
    {
        $treeBuilder->getRootNode()
            ->children()
                ->booleanNode('can_add_receiver')->defaultTrue()->end()
                ->booleanNode('can_modify_receiver')->defaultTrue()->end()
            ->end();
    }

}
