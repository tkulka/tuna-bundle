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
                        ->scalarNode('editor_config')
                            ->defaultValue('bundles/thecodeineeditor/js/editorConfig.js')
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('host')
                    ->defaultNull()
                ->end()
                ->scalarNode('menu_builder')
                    ->defaultValue('TheCodeine\AdminBundle\Menu\Builder')
                ->end()
                ->arrayNode('locales')
                    ->prototype('scalar')->end()
                    ->defaultValue(['en', 'pl'])
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
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('enabled')->defaultTrue()->end()
                    ->booleanNode('create')->defaultTrue()->end()
                    ->booleanNode('delete')->defaultTrue()->end()
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
                    ->booleanNode('enabled')->defaultTrue()->end()
                    ->scalarNode('default_template')
                        ->defaultValue('TheCodeineMenuBundle:Menu:render_menu.html.twig')
                    ->end()
                ->end()
            ->end()
        ;

        $sections->children()
            ->arrayNode('security')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('enabled')->defaultTrue()->end()
                    ->booleanNode('use_access_control')->defaultTrue()->end()
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
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('enabled')->defaultValue($defaultValue)->end()
            ->end()
        ;
    }
}
