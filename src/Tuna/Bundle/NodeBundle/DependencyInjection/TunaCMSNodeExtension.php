<?php

namespace TunaCMS\Bundle\NodeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class TunaCMSNodeExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    public function prepend(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig('tuna_cms_node');
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('tuna_cms_node.types.node.model', $config['types']['node']['model']);
        $container->setParameter('tuna_cms_node.types', $config['types']);

        $menuConfig = [
            'types' => [
                'node' => $config['menu'],
            ]
        ];

        $container->prependExtensionConfig('tuna_cms_menu', $menuConfig);

        $doctrineConfig = [
            'orm' => [
                'resolve_target_entities' => [
                    'TunaCMS\Bundle\NodeBundle\Model\NodeInterface' => $config['types']['node']['model'],
                    'TunaCMS\Bundle\NodeBundle\Model\MetadataInterface' => $config['metadata']['model'],
                    'TunaCMS\Bundle\NodeBundle\Model\MenuNodeInterface' => $config['menu']['model'],
                ],
            ],
        ];

        $container->prependExtensionConfig('doctrine', $doctrineConfig);
    }
}
