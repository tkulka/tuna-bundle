<?php

namespace TheCodeine\MenuBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('the_codeine_menu');

        $rootNode
            ->children()
            ->scalarNode('model')->cannotBeEmpty()->isRequired()->end()
            ->scalarNode('form')->cannotBeEmpty()->isRequired()->defaultValue('TheCodeine\MenuBundle\Form\MenuType')->end()
            ->scalarNode('manager_class')->defaultValue('TheCodeine\MenuBundle\Service\MenuManager')->end()
            ->end();

        return $treeBuilder;
    }
}
