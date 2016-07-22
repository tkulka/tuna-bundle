<?php
// @formatter:off

namespace TheCodeine\FileBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('the_codeine_file');
        $rootNode->children()
            ->arrayNode('file_manager')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('web_root_dir')->defaultValue('%kernel.root_dir%/../web')->end()
                    ->scalarNode('tmp_path')->defaultValue('uploads/tmp')->end()
                    ->scalarNode('files_path')->defaultValue('uploads/files')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
