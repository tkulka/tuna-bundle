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
                ->arrayNode('types')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('model')->cannotBeEmpty()->isRequired()->end()
                            ->scalarNode('form')->defaultValue('TunaCMS\Bundle\MenuBundle\Form\MenuType')->end()
                            ->arrayNode('templates')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('tree_item')->defaultValue('@TunaCMSMenu/tree_item.html.twig')->end()
                                    ->scalarNode('edit')->defaultValue('@TunaCMSMenu/edit.html.twig')->end()
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
