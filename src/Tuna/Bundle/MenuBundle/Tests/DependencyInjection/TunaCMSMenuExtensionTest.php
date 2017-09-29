<?php

namespace TunaCMS\Bundle\MenuBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use TunaCMS\Bundle\MenuBundle\DependencyInjection\TunaCMSMenuExtension;

class TunaCMSMenuExtensionTest extends TestCase
{
    public function testMenuLoadTypes()
    {
        $configuration = $this->getConfiguration();

        $this->assertEquals('App\Model\Menu', $configuration->getParameter('tuna_cms_menu.types.menu.model'));
        $this->assertEquals(['menu', 'node'], array_keys($configuration->getParameter('tuna_cms_menu.types')));
    }

    public function testMenuLoadTemplates()
    {
        $configuration = $this->getConfiguration(
            [
                'templates' => [
                    'menu' => 'menu.html.twig',
                    'menu_item' => 'menu_item.html.twig',
                ],
            ]
        );

        $this->assertEquals(
            [
                'menu' => 'menu.html.twig',
                'menu_item' => 'menu_item.html.twig',
            ],
            $configuration->getParameter('tuna_cms_menu.templates')
        );
    }

    public function testCustomMenuManager()
    {
        $configuration = $this->getConfiguration(
            [
                'menu_manager' => 'app.user_manager',
            ]
        );

        $this->assertSame(
            'app.user_manager',
            (string)$configuration->getAlias('tuna_cms_menu.menu_manager'),
            'tuna_cms_menu.menu_manager alias is correct'
        );
    }

    public function testLoadDoctrineResolveTargetEntities()
    {
        $configuration = $this->getConfiguration();

        $this->assertEquals(
            [
                [
                    'orm' => [
                        'resolve_target_entities' => [
                            'TunaCMS\Bundle\MenuBundle\Model\MenuInterface' => 'App\Model\Menu',
                        ],
                    ],
                ],
            ],
            $configuration->getExtensionConfig('doctrine')
        );
    }

    /**
     * @param array $config
     *
     * @return ContainerBuilder
     */
    private function getConfiguration(array $config = [])
    {
        $config += [
            'types' => [
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
            ],
        ];

        $configuration = new ContainerBuilder();
        $configuration->prependExtensionConfig(
            'tuna_cms_menu',
            $config
        );

        $loader = new TunaCMSMenuExtension();
        $loader->prepend($configuration);
        $loader->load(
            [
                'tuna_cms_menu' => $config,
            ],
            $configuration
        );

        return $configuration;
    }
}
