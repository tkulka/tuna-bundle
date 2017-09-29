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
                ->scalarNode('menu_manager')->cannotBeEmpty()->defaultValue('tuna_cms_menu.menu_manager.default')->end()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('menu')->cannotBeEmpty()->defaultValue('tuna-bundle/Resources/views/_frontend/menu/menu.html.twig')->end()
                        ->scalarNode('menu_item')->cannotBeEmpty()->defaultValue('tuna-bundle/Resources/views/_frontend/menu/menu_item.html.twig')->end()
                    ->end()
                ->end()
                ->arrayNode('types')
                    ->isRequired()
                    ->validate()
                    ->ifTrue(function ($v) {
                        return !isset($v['menu']);
                    })
                        ->thenInvalid('The child node "menu" at path "tuna_cms_menu.types" must be configured.')
                    ->end()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('model')->cannotBeEmpty()->isRequired()->end()
                            ->scalarNode('form')->cannotBeEmpty()->defaultValue('TunaCMS\Bundle\MenuBundle\Form\MenuType')->end()
                            ->arrayNode('templates')
                                ->isRequired()
                                ->children()
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
