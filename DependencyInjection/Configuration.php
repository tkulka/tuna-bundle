<?php

namespace TunaCMS\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
        $rootNode = $treeBuilder->root('tuna_cms_admin');
        // @formatter:off
        $rootNode
            ->children()
                ->arrayNode('paths')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('admin_logo')->defaultValue('bundles/tunacmsadmin/images/logo.png')->end()
                        ->scalarNode('editor_config')->defaultValue('bundles/tunacmseditor/js/editorConfig.js')->end()
                    ->end()
                ->end()
                ->scalarNode('host')->defaultNull()->end()
                ->scalarNode('menu_builder')->defaultValue('TunaCMS\AdminBundle\Menu\Builder')->end()
                ->scalarNode('locale')->defaultValue('en')->end()
                ->arrayNode('locales')
                    ->prototype('scalar')->end()
                    ->defaultValue(['en', 'pl'])
                ->end()
                ->arrayNode('form')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('templates')
                            ->defaultValue(['TunaCMSAdminBundle::_partials/form/fields.html.twig'])
                            ->prototype('scalar')
                        ->end()
                    ->end()
                ->end()
            ->end();
        // @formatter:on

        $this->addComponentsSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $rootNode
     */
    protected function addComponentsSection(ArrayNodeDefinition $rootNode)
    {
        // @formatter:off
        $sections = $rootNode->children()
            ->arrayNode('components')
            ->addDefaultsIfNotSet()
        ;

        $sections->children()
            ->arrayNode('editor')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('wysiwyg_style_dir')
                        ->defaultValue('%kernel.root_dir%/../vendor/tuna-cms/tuna-bundle/Resources/public/sass/editor')
                    ->end()
                ->end()
            ->end()
        ;

        $sections->children()
            ->arrayNode('menu')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('enabled')->defaultFalse()->end()
                    ->scalarNode('default_template')
                        ->defaultValue('TunaCMSMenuBundle:Menu:render_menu.html.twig')
                    ->end()
                    ->arrayNode('bundle')
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
            ->end()
        ;

        // @formatter:on

        $this->addEnabledConfig($sections, 'news', true);
        $this->addEnabledConfig($sections, 'events', false);
        $this->addEnabledConfig($sections, 'translations', true);
        $this->addEnabledConfig($sections, 'categories', false);
    }

    /**
     * @param ArrayNodeDefinition $node
     * @param $name
     * @param $defaultValue
     */
    protected function addEnabledConfig(ArrayNodeDefinition $node, $name, $defaultValue)
    {
        // @formatter:off
        $node->children()
            ->arrayNode($name)
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('enabled')->defaultValue($defaultValue)->end()
            ->end()
        ;
        // @formatter:on
    }
}
