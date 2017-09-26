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
                ->scalarNode('manager_class')->defaultValue('TunaCMS\Bundle\MenuBundle\Service\MenuManager')->end()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('menu')->defaultNull()->end()
                        ->scalarNode('menu_item')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('types')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('model')->cannotBeEmpty()->isRequired()->end()
                            ->scalarNode('form')->defaultValue('TunaCMS\Bundle\MenuBundle\Form\MenuType')->end()
                            ->arrayNode('templates')
                                ->isRequired()
                                ->children()
                                    ->scalarNode('tree_item')->defaultNull()->end()
                                    ->scalarNode('edit')->isRequired()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        // @formatter:on

        return $treeBuilder;
    }
}
