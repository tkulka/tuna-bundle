<?php

namespace TheCodeine\AdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

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

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    public function prepend(ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('config.yml');

        $configs = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration(new Configuration(), $configs);

        $this->loadSecurityComponent($config['components']['security'], $loader);
    }

    /**
     * @param array            $config
     * @param YamlFileLoader    $loader
     */
    private function loadSecurityComponent(array $config, YamlFileLoader $loader)
    {
        if (!$config['enabled']) {
            return;
        }

        $loader->load('security.yml');

        if (!$config['use_access_control']) {
            return;
        }

        $loader->load('access_control.yml');
    }

    private function setParameters(ContainerBuilder $container, array $config)
    {
        $config += $this->flattenArray($config);
        foreach ($config as $key => $value) {
            $container->setParameter("the_codeine_admin.$key", $value);
        }
        $container->setParameter('the_codeine_admin.menu_builder.class', $config['menu_builder']);
        $container->setParameter('the_codeine_admin.locales', $config['locales']);
    }

    private function flattenArray($array, $prefix = '')
    {
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = $result + $this->flattenArray($value, $prefix . $key . '.');
            } else {
                $result[$prefix . $key] = is_bool($value) ? ($value ? 1 : 0) : $value;
            }
        }
        return $result;
    }
}
