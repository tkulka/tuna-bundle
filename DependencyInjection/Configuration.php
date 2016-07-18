<?php
// @formatter:off

namespace TheCodeine\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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

        $rootNode
            ->children()
                ->arrayNode('paths')
                    ->children()
                        ->scalarNode('admin_logo')
                            ->defaultValue('bundles/thecodeineadmin/images/logo.png')
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('host')
                    ->defaultValue(null)
                ->end()
                ->scalarNode('menu_builder')
                    ->defaultValue('TheCodeine\AdminBundle\Menu\Builder')
                ->end()
            ->end();

        $this->addComponentsSection($rootNode);

        return $treeBuilder;
    }

    private function addComponentsSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('components')
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('page')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('menu_link')->defaultValue(true)->end()
                        ->booleanNode('create')->defaultValue(false)->end()
                        ->booleanNode('delete')->defaultValue(false)->end()
                    ->end()
                ->end()
                ->arrayNode('news')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultValue(true)->end()
                    ->end()
                ->end()
                ->arrayNode('event')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultValue(false)->end()
                    ->end()
                ->end()
                ->arrayNode('translations')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('menu_link')->defaultValue(true)->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addComponent(ArrayNodeDefinition $componentsSection, $name, $menuLink, $create, $delete)
    {
        $componentsSection
            ->children()
                ->arrayNode($name)
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('menu_link')->defaultValue($menuLink)->end()
                        ->booleanNode('create')->defaultValue($create)->end()
                        ->booleanNode('delete')->defaultValue($delete)->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
