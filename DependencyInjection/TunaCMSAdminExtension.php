<?php

namespace TunaCMS\AdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class TunaCMSAdminExtension extends Extension implements PrependExtensionInterface
{
    /**
     * Add here tuna array parameters that can be reused in configs, i.e. user can configure:
     *
     *      tuna_cms_admin:
     *          locales: "%locales"
     *
     * and tuna is using this parameter in lexik_translation config:
     *
     *      lexik_translation:
     *          managed_locales: "%tuna_cms_admin.locales%"
     *
     * @var array
     */
    protected static $ARRAY_PARAMETERS = [
        'locales',
    ];

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $configs = $container->getExtensionConfig($this->getAlias());
        $this->resolveArrayParameters($container, $configs);
        $config = $this->processConfiguration(new Configuration(), $configs);

        $this->setParameters($container, $config);
    }

    /**
     * @param ContainerBuilder $container
     * @param array $config
     */
    protected function setParameters(ContainerBuilder $container, array $config)
    {
        $container->setParameter('tuna_cms_admin', $config);

        $config += $this->flattenArray($config);
        foreach ($config as $key => $value) {
            $container->setParameter('tuna_cms_admin.'.$key, $value);
        }
        $container->setParameter('tuna_cms_admin.menu_builder.class', $config['menu_builder']);
    }

    /**
     * @param $array
     * @param string $prefix
     *
     * @return array
     */
    protected function flattenArray($array, $prefix = '')
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = $result + $this->flattenArray($value, $prefix.$key.'.');
            } else {
                $result[$prefix.$key] = is_bool($value) ? ($value ? 1 : 0) : $value;
            }
        }

        return $result;
    }

    /**
     * @param ContainerBuilder $container
     * @param $configs
     */
    protected function resolveArrayParameters(ContainerBuilder $container, &$configs)
    {
        foreach ($configs as &$config) {
            foreach (self::$ARRAY_PARAMETERS as $parameter) {
                if (array_key_exists($parameter, $config)) {
                    $config[$parameter] = $container->getParameterBag()->resolveValue($config[$parameter]);
                }
            }
        }
    }
}
