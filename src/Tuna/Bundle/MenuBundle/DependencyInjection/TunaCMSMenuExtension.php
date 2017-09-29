<?php

namespace TunaCMS\Bundle\MenuBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class TunaCMSMenuExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setAlias('tuna_cms_menu.menu_manager', $config['menu_manager']);
        $container->setParameter('tuna_cms_menu.types.menu.model', $config['types']['menu']['model']);
        $container->setParameter('tuna_cms_menu.types', $config['types']);

        $container->getDefinition('tuna.menu.twig')
            ->replaceArgument(4, $config['templates'])
        ;
    }

    public function prepend(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig('tuna_cms_menu');
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $doctrineConfig = [
            'orm' => [
                'resolve_target_entities' => [
                    'TunaCMS\Bundle\MenuBundle\Model\MenuInterface' => $config['types']['menu']['model'],
                ],
            ],
        ];

        $container->prependExtensionConfig('doctrine', $doctrineConfig);
    }
}
