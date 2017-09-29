<?php

namespace TunaCMS\Bundle\MenuBundle\Tests\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use TunaCMS\Bundle\MenuBundle\DependencyInjection\Compiler\MenuTypesPass;

class MenuTypesPassTest extends TestCase
{
    public function testProcessWithTypesParameter()
    {
        $container = new ContainerBuilder();
        $container->addCompilerPass(new MenuTypesPass());
        $container->setParameter('tuna_cms_menu.types', [
            'menu' => [
                'model' => 'App\Model\Menu',
                'templates' => [
                    'edit' => 'menu_edit.html.twig',
                ],
            ],
            'node' => [
                'model' => 'App\Model\Node',
                'templates' => [
                    'edit' => 'node_edit.html.twig',
                ],
            ],
        ]);

        $definition = new Definition('TunaCMS\Bundle\MenuBundle\Factory\MenuFactory');
        $container->setDefinition('tuna_cms_bundle_menu.factory.menu_factory', $definition);

        $container->compile();

        $this->assertCount(2, $definition->getMethodCalls());
        $this->assertTrue($definition->hasMethodCall('registerType'));
    }
}
