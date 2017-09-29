<?php

namespace TunaCMS\Bundle\NodeBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use TunaCMS\Bundle\NodeBundle\DependencyInjection\TunaCMSNodeExtension;

class TunaCMSNodeExtensionTest extends TestCase
{
    public function testNodeLoadTypes()
    {
        $configuration = $this->getConfiguration();

        $this->assertEquals('App\Model\Node', $configuration->getParameter('tuna_cms_node.types.node.model'));
        $this->assertEquals(['node', 'page'], array_keys($configuration->getParameter('tuna_cms_node.types')));
    }

    public function testLoadDoctrineResolveTargetEntities()
    {
        $configuration = $this->getConfiguration();

        $this->assertEquals(
            [
                [
                    'orm' => [
                        'resolve_target_entities' => [
                            'TunaCMS\Bundle\NodeBundle\Model\NodeInterface' => 'App\Model\Node',
                            'TunaCMS\Bundle\NodeBundle\Model\MetadataInterface' => 'App\Model\Metadata',
                            'TunaCMS\Bundle\NodeBundle\Model\MenuNodeInterface' => 'App\Model\MenuNode',
                        ],
                    ],
                ],
            ],
            $configuration->getExtensionConfig('doctrine')
        );
    }

    public function testLoadMenuNodeTypes()
    {
        $configuration = $this->getConfiguration();

        $this->assertEquals(
            [
                [
                    'types' => [
                        'node' => [
                            'model' => 'App\Model\MenuNode',
                            'form' => 'App\Form\MenuNodeType',
                            'templates' => [
                                'edit' => 'menu-node-edit.html.twig',
                            ],
                        ],
                    ],
                ],
            ],
            $configuration->getExtensionConfig('tuna_cms_menu')
        );
    }

    /**
     * @return ContainerBuilder
     */
    private function getConfiguration()
    {
        $configuration = new ContainerBuilder();

        $configuration->prependExtensionConfig(
            'tuna_cms_node',
            [
                'metadata' => [
                    'model' => 'App\Model\Metadata',
                ],
                'menu' => [
                    'model' => 'App\Model\MenuNode',
                    'form' => 'App\Form\MenuNodeType',
                    'templates' => [
                        'edit' => 'menu-node-edit.html.twig',
                    ],
                ],
                'types' => [
                    'node' => [
                        'model' => 'App\Model\Node',
                        'templates' => [
                            'edit' => 'node-edit.html.twig',
                        ],
                    ],
                    'page' => [
                        'model' => 'App\Model\Page',
                        'templates' => [
                            'edit' => 'page-edit.html.twig',
                        ],
                    ],
                ],
            ]
        );

        $loader = new TunaCMSNodeExtension();
        $loader->prepend($configuration);

        return $configuration;
    }
}
