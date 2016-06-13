<?php

namespace TheCodeine\EditorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class TheCodeineEditorExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->registerEditorParameters($config, $container);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }

    private function registerEditorParameters($config, ContainerBuilder $container)
    {
        $container->setParameter('the_codeine_editor.options.autoinclude', !$config['autoinclude']);
        $container->setParameter('the_codeine_editor.options.standalone', $config['standalone']);
        $container->setParameter('the_codeine_editor.options.base_path', $config['base_path']);
    }
}
