<?php

namespace TheCodeine\FileBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('the_codeine_file');

        $rootNode
            ->children()
            ->arrayNode('file_manager')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('tmp_path')->defaultValue('uploads/tmp')->end()
                    ->scalarNode('web_root_dir')->defaultValue('%kernel.root_dir%/../web')->end()
                    ->scalarNode('upload_files_path')->defaultValue('uploads/files')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
