<?php


namespace TheCodeine\EditorBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('the_codeine_editor');

        $rootNode
            ->children()
            ->booleanNode('autoinclude')->defaultTrue()->end()
            ->booleanNode('standalone')->defaultFalse()->end()
            ->scalarNode('base_path')->defaultValue('bundles/thecodeineeditor')->end()
            ->booleanNode('debug')->defaultFalse()->end()
            ->booleanNode('noconflict')->defaultTrue()->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
