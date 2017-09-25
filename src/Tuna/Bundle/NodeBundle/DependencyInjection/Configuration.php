<?php

namespace TunaCMS\Bundle\NodeBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('tuna_cms_node');

        // @formatter:off
        $rootNode->children()
            ->arrayNode('metadata')
                ->children()
                    ->scalarNode('model')->isRequired()->end()
                ->end()
            ->end()
            ->arrayNode('types')
                ->useAttributeAsKey('name')
                ->prototype('array')
                    ->children()
                        ->scalarNode('model')->cannotBeEmpty()->isRequired()->end()
                        ->scalarNode('form')->defaultValue('TunaCMS\Bundle\NodeBundle\Form\NodeType')->end()
                        ->arrayNode('templates')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('index')->defaultValue('@TunaCMSAdmin/node/index.html.twig')->end()
                                ->scalarNode('edit')->defaultValue('@TunaCMSAdmin/node/edit.html.twig')->end()
                                ->scalarNode('node_item')->defaultValue('@TunaCMSAdmin/node/node_item.html.twig')->end()
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
