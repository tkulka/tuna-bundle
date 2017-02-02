<?php

namespace TheCodeine\VideoBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('the_codeine_video');

        $rootNode
            ->children()
                ->scalarNode('manager_class')->defaultValue('TheCodeine\VideoBundle\Doctrine\VideoManager')->end()
                ->scalarNode('form_type_class')->defaultValue('TheCodeine\VideoBundle\Form\VideoUrlType')->end()
                ->scalarNode('entity_manager_class')->defaultValue('Doctrine\ORM\EntityManager')->end()
                ->scalarNode('twig_extension_class')->defaultValue('TheCodeine\VideoBundle\Twig\Extension\VideoPlayerExtension')->end()
            ->end();

        return $treeBuilder;
    }
}
