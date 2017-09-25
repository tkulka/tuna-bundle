<?php

namespace TunaCMS\Bundle\NodeBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NodeTypesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->registerMenuTypes($container);
    }

    protected function registerMenuTypes(ContainerBuilder $container)
    {
        $nodeTypesConfig = $container->getParameter('tuna_cms_node.types');
        $nodeFactoryDefinition = $container->findDefinition('tuna_cms_bundle_node.factory.node_factory');

        foreach ($nodeTypesConfig as $type => $config) {
            $nodeFactoryDefinition->addMethodCall('registerType', [
                $type,
                $config,
            ]);
        }
    }
}
