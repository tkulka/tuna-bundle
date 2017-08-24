<?php

namespace TunaCMS\Bundle\VideoBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('tuna_cms_video');

        $rootNode
            ->children()
            ->scalarNode('manager_class')->defaultValue('TunaCMS\Bundle\VideoBundle\Doctrine\VideoManager')->end()
            ->scalarNode('form_type_class')->defaultValue('TunaCMS\Bundle\VideoBundle\Form\VideoUrlType')->end()
                ->scalarNode('entity_manager_class')->defaultValue('Doctrine\ORM\EntityManager')->end()
            ->scalarNode('twig_extension_class')->defaultValue('TunaCMS\Bundle\VideoBundle\Twig\Extension\VideoPlayerExtension')->end()
            ->end();

        return $treeBuilder;
    }
}
