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
                ->isRequired()
                ->children()
                    ->scalarNode('model')->cannotBeEmpty()->isRequired()->end()
                ->end()
            ->end()
            ->arrayNode('menu')
                ->isRequired()
                ->children()
                    ->scalarNode('model')->cannotBeEmpty()->isRequired()->end()
                    ->scalarNode('form')->cannotBeEmpty()->defaultValue('TunaCMS\Bundle\NodeBundle\Form\MenuNodeType')->end()
                    ->arrayNode('templates')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('edit')->cannotBeEmpty()->defaultValue('@TunaCMSAdmin/node/menu/edit.html.twig')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('types')
                ->isRequired()
                ->validate()
                ->ifTrue(function ($v) {
                    return !isset($v['node']);
                })
                    ->thenInvalid('The child node "node" at path "tuna_cms_node.types" must be configured.')
                ->end()
                ->useAttributeAsKey('name')
                ->prototype('array')
                    ->children()
                        ->scalarNode('model')->cannotBeEmpty()->isRequired()->end()
                        ->scalarNode('form')->cannotBeEmpty()->defaultValue('TunaCMS\Bundle\NodeBundle\Form\NodeType')->end()
                        ->arrayNode('templates')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('edit')->cannotBeEmpty()->defaultValue('@TunaCMSAdmin/node/edit.html.twig')->end()
                                ->scalarNode('node_item')->cannotBeEmpty()->defaultValue('@TunaCMSAdmin/node/node_item.html.twig')->end()
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
