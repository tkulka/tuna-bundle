<?php

namespace TunaCMS\Bundle\MenuBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('tuna_cms_menu');

        // @formatter:off
        $rootNode
            ->children()
                ->scalarNode('model')->cannotBeEmpty()->isRequired()->end()
                ->scalarNode('manager_class')->defaultValue('TunaCMS\Bundle\MenuBundle\Service\MenuManager')->end()
            ->end();
        // @formatter:on

        return $treeBuilder;
    }
}
