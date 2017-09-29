<?php

namespace TunaCMS\Bundle\MenuBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use TunaCMS\Bundle\MenuBundle\DependencyInjection\Configuration;

class ConfigurationTest extends TestCase
{
    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The child node "types" at path "tuna_cms_menu" must be configured.
     */
    public function testEmptyConfiguration()
    {
        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), []);
    }

    /**
     * @dataProvider getValidTestData
     *
     * @param array $data
     * @param array $expected
     */
    public function testValidConfiguration(array $data, array $expected)
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(
            new Configuration(),
            [
                $data,
            ]
        );
        $this->assertEquals($expected, $config);
    }

    public function getValidTestData()
    {
        return [
            [
                [
                    'types' => [
                        'menu' => [
                            'model' => 'App\Model\Menu',
                            'templates' => [
                                'edit' => 'menu_edit.html.twig',
                            ],
                        ],
                    ],
                ],
                [
                    'menu_manager' => 'tuna_cms_menu.menu_manager.default',
                    'templates' => [
                        'menu' => 'tuna-bundle/Resources/views/_frontend/menu/menu.html.twig',
                        'menu_item' => 'tuna-bundle/Resources/views/_frontend/menu/menu_item.html.twig',
                    ],
                    'types' => [
                        'menu' => [
                            'model' => 'App\Model\Menu',
                            'templates' => [
                                'edit' => 'menu_edit.html.twig',
                            ],
                            'form' => 'TunaCMS\Bundle\MenuBundle\Form\MenuType',
                        ],
                    ],
                ],
            ],
            [
                [
                    'menu_manager' => 'app.menu_manager',
                    'templates' => [
                        'menu' => 'menu.html.twig',
                        'menu_item' => 'menu_item.html.twig',
                    ],
                    'types' => [
                        'node' => [
                            'model' => 'App\Model\Node',
                            'form' => 'App\Form\MenuType',
                            'templates' => [
                                'edit' => 'node_edit.html.twig',
                            ],
                        ],
                        'menu' => [
                            'model' => 'App\Model\Menu',
                            'templates' => [
                                'edit' => 'menu_edit.html.twig',
                            ],
                        ],
                    ],
                ],
                [
                    'menu_manager' => 'app.menu_manager',
                    'templates' => [
                        'menu' => 'menu.html.twig',
                        'menu_item' => 'menu_item.html.twig',
                    ],
                    'types' => [
                        'node' => [
                            'model' => 'App\Model\Node',
                            'form' => 'App\Form\MenuType',
                            'templates' => [
                                'edit' => 'node_edit.html.twig',
                            ],
                        ],
                        'menu' => [
                            'model' => 'App\Model\Menu',
                            'templates' => [
                                'edit' => 'menu_edit.html.twig',
                            ],
                            'form' => 'TunaCMS\Bundle\MenuBundle\Form\MenuType',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getInvalidTestData
     *
     * @param array $data
     * @param string $exceptionMessage
     */
    public function testInvalidConfiguration(array $data, $exceptionMessage)
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $processor = new Processor();
        $processor->processConfiguration(
            new Configuration(),
            [
                $data,
            ]
        );
    }

    public function getInvalidTestData()
    {
        return [
            [
                [
                    'menu_manager' => '',
                ],
                'The path "tuna_cms_menu.menu_manager" cannot contain an empty value, but got "".',
            ],
            [
                [
                    'templates' => [
                        'menu' => '',
                    ],
                ],
                'The path "tuna_cms_menu.templates.menu" cannot contain an empty value, but got "".',
            ],
            [
                [
                    'templates' => [
                        'menu_item' => '',
                    ],
                ],
                'The path "tuna_cms_menu.templates.menu_item" cannot contain an empty value, but got "".',
            ],
            [
                [
                    'types' => [
                        'menu' => [
                            'model' => '',
                        ],
                    ],
                ],
                'The path "tuna_cms_menu.types.menu.model" cannot contain an empty value, but got "".',
            ],
            [
                [
                    'types' => [
                        'menu' => [
                            'model' => 'App\Model\Menu',
                            'form' => '',
                        ],
                    ],
                ],
                'The path "tuna_cms_menu.types.menu.form" cannot contain an empty value, but got "".',
            ],
            [
                [
                    'types' => [
                        'menu' => [
                            'model' => 'App\Model\Menu',
                        ],
                    ],
                ],
                'The child node "templates" at path "tuna_cms_menu.types.menu" must be configured.',
            ],
            [
                [
                    'types' => [
                        'menu' => [
                            'model' => 'App\Model\Menu',
                            'templates' => [],
                        ],
                    ],
                ],
                'The child node "edit" at path "tuna_cms_menu.types.menu.templates" must be configured.',
            ],
            [
                [
                    'types' => [
                        'node' => [
                            'model' => 'App\Model\Node',
                            'templates' => [
                                'edit' => 'node_edit.html.twig',
                            ],
                        ],
                    ],
                ],
                'The child node "menu" at path "tuna_cms_menu.types" must be configured.',
            ],
        ];
    }
}
