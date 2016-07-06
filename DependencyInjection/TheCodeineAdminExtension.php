<?php

namespace TheCodeine\AdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class TheCodeineAdminExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->setParameters($container, $config);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }


    public function prepend(ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('config.yml');
    }

    private function setParameters(ContainerBuilder $container, array $config)
    {
        $container->setParameter('the_codeine_admin.paths', $config['paths']);
        $container->setParameter('the_codeine_admin.host', $config['host']);
        $container->setParameter('the_codeine_admin.enable_translations', $config['enable_translations']);
        $container->setParameter('the_codeine_admin.enable_translations_string', $config['enable_translations'] ? 'true' : 'false');
    }
}
