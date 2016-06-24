<?php

namespace TheCodeine\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('the_codeine_admin');

        // @formatter:off
        $rootNode
            ->children()
            ->arrayNode('paths')
            ->children()
            ->scalarNode('admin_logo')
            ->defaultValue('bundles/thecodeineadmin/images/logo.png')
            ->end()
            ->end()
            ->end()
            ->booleanNode('enable_translations')
            ->defaultValue(true)
            ->end()
            ->end();
        // @formatter:on

        return $treeBuilder;
    }
}
