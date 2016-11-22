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
                    ->addDefaultsIfNotSet()
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
        $sections = $rootNode->children()
            ->arrayNode('components')
            ->addDefaultsIfNotSet()
        ;

        $sections->children()
            ->arrayNode('pages')
                ->beforeNormalization()
                    ->ifTrue(function ($v) { return is_bool($v); })
                    ->then(function ($v) { return array('enabled' => $v); })
                ->end()
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('enabled')->defaultValue(true)->end()
                    ->booleanNode('create')->defaultValue(true)->end()
                    ->booleanNode('delete')->defaultValue(true)->end()
                ->end()
            ->end()
        ;

        $sections->children()
            ->arrayNode('editor')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('wysiwyg_style_dir')
                        ->defaultValue('%kernel.root_dir%/../vendor/thecodeine/tuna-adminbundle/Resources/public/sass/editor')
                    ->end()
                ->end()
            ->end()
        ;

        $sections->children()
            ->arrayNode('menu')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('enabled')->defaultValue(true)->end()
                    ->scalarNode('default_template')
                        ->defaultValue('TheCodeineMenuBundle:Menu:render_menu.html.twig')
                    ->end()
                ->end()
            ->end()
        ;

        $this->addEnabledConfig($sections, 'news', true);
        $this->addEnabledConfig($sections, 'events', false);
        $this->addEnabledConfig($sections, 'translations', true);
        $this->addEnabledConfig($sections, 'categories', false);
    }

    private function addEnabledConfig(ArrayNodeDefinition $node, $name, $defaultValue)
    {
        $node->children()
            ->arrayNode($name)
                ->beforeNormalization()
                    ->ifTrue(function ($v) { return is_bool($v); })
                    ->then(function ($v) { return array('enabled' => $v); })
                ->end()
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('enabled')->defaultValue($defaultValue)->end()
            ->end()
        ;
    }
}
