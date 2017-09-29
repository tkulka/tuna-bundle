<?php

namespace TunaCMS\Bundle\NodeBundle\Tests\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use TunaCMS\Bundle\NodeBundle\DependencyInjection\Compiler\NodeTypesPass;

class NodeTypesPassTest extends TestCase
{
    public function testProcessWithTypesParameter()
    {
        $container = new ContainerBuilder();
        $container->addCompilerPass(new NodeTypesPass());
        $container->setParameter('tuna_cms_node.types', [
            'node' => [
                'model' => 'App\Model\Node',
                'templates' => [
                    'edit' => 'node-edit.html.twig',
                ],
            ],
            'Page' => [
                'model' => 'App\Model\Page',
                'templates' => [
                    'edit' => 'page-edit.html.twig',
                ],
            ],
        ]);

        $definition = new Definition('TunaCMS\Bundle\NodeBundle\Factory\NodeFactory');
        $container->setDefinition('tuna_cms_bundle_node.factory.node_factory', $definition);

        $container->compile();

        $this->assertCount(2, $definition->getMethodCalls());
        $this->assertTrue($definition->hasMethodCall('registerType'));
    }
}
