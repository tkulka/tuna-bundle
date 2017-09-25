<?php

namespace TunaCMS\Bundle\MenuBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MenuTypesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->registerMenuTypes($container);
    }

    protected function registerMenuTypes(ContainerBuilder $container)
    {
        $menuTypesConfig = $container->getParameter('tuna_cms_menu.types');
        $menuFactoryDefinition = $container->findDefinition('tuna_cms_bundle_menu.factory.menu_factory');

        foreach ($menuTypesConfig as $type => $config) {
            $menuFactoryDefinition->addMethodCall('registerType', [
                $type,
                $config,
            ]);
        }
    }
}
