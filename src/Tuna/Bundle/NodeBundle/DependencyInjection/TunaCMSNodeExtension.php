<?php

namespace TunaCMS\Bundle\NodeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use TunaCMS\CommonComponent\Helper\ArrayHelper;

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

        $this->setParameters($container, $config);

        if (!array_key_exists('node', $config['node_types'])) {
            throw new \LogicException('You have to provide `tuna_cms_node.node_types.node` configuration.');
        }

        $doctrineConfig = [
            'orm' => [
                'resolve_target_entities' => [
                    'TunaCMS\Bundle\NodeBundle\Model\NodeInterface' => $config['node_types']['node']['model'],
                    'TunaCMS\Bundle\NodeBundle\Model\MetadataInterface' => $config['metadata']['model'],
                ],
            ],
        ];

        $container->prependExtensionConfig('doctrine', $doctrineConfig);
    }

    /**
     * @param ContainerBuilder $container
     * @param array $config
     */
    protected function setParameters(ContainerBuilder $container, array $config)
    {
        $config += ArrayHelper::flattenArray($config);
        foreach ($config as $key => $value) {
            $container->setParameter('tuna_cms_node.'.$key, $value);
        }
    }
}
