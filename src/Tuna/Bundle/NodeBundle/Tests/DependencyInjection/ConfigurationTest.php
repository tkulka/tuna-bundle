<?php

namespace TunaCMS\Bundle\NodeBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use TunaCMS\Bundle\NodeBundle\DependencyInjection\Configuration;

class ConfigurationTest extends TestCase
{
    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The child node "metadata" at path "tuna_cms_node" must be configured.
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
                    'metadata' => [
                        'model' => 'App\Model\Metadata',
                    ],
                    'menu' => [
                        'model' => 'App\Model\MenuNode'
                    ],
                    'types' => [
                        'node' => [
                            'model' => 'App\Model\Node',
                        ],
                    ],
                ],
                [
                    'metadata' => [
                        'model' => 'App\Model\Metadata',
                    ],
                    'menu' => [
                        'model' => 'App\Model\MenuNode',
                        'form' => 'TunaCMS\Bundle\NodeBundle\Form\MenuNodeType',
                        'templates' => [
                            'edit' => '@TunaCMSAdmin/node/menu/edit.html.twig',
                        ],
                    ],
                    'types' => [
                        'node' => [
                            'model' => 'App\Model\Node',
                            'form' => 'TunaCMS\Bundle\NodeBundle\Form\NodeType',
                            'templates' => [
                                'edit' => '@TunaCMSAdmin/node/edit.html.twig',
                                'node_item' => '@TunaCMSAdmin/node/node_item.html.twig',
                            ],
                        ],
                    ],
                ],
            ],
            [
                [
                    'metadata' => [
                        'model' => 'App\Model\Metadata',
                    ],
                    'menu' => [
                        'model' => 'App\Model\MenuNode',
                        'form' => 'App\Form\MenuNodeType',
                        'templates' => [
                            'edit' => 'menu-edit.html.twig',
                        ],
                    ],
                    'types' => [
                        'node' => [
                            'model' => 'App\Model\Node',
                            'form' => 'App\Form\NodeType',
                            'templates' => [
                                'edit' => 'node-edit.html.twig',
                                'node_item' => 'node_item.html.twig',
                            ],
                        ],
                    ],
                ],
                [
                    'metadata' => [
                        'model' => 'App\Model\Metadata',
                    ],
                    'menu' => [
                        'model' => 'App\Model\MenuNode',
                        'form' => 'App\Form\MenuNodeType',
                        'templates' => [
                            'edit' => 'menu-edit.html.twig',
                        ],
                    ],
                    'types' => [
                        'node' => [
                            'model' => 'App\Model\Node',
                            'form' => 'App\Form\NodeType',
                            'templates' => [
                                'edit' => 'node-edit.html.twig',
                                'node_item' => 'node_item.html.twig',
                            ],
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
                    'metadata' => [
                        'model' => '',
                    ],
                ],
                'The path "tuna_cms_node.metadata.model" cannot contain an empty value, but got "".',
            ],
            [
                [
                    'metadata' => [
                        'model' => 'App\Model\Metadata',
                    ],
                ],
                'The child node "menu" at path "tuna_cms_node" must be configured.',
            ],
            [
                [
                    'metadata' => [
                        'model' => 'App\Model\Metadata',
                    ],
                    'menu' => [],
                ],
                'The child node "model" at path "tuna_cms_node.menu" must be configured.',
            ],
            [
                [
                    'metadata' => [
                        'model' => 'App\Model\Metadata',
                    ],
                    'menu' => [
                        'model' => 'App\Model\MenuNode'
                    ],
                ],
                'The child node "types" at path "tuna_cms_node" must be configured.',
            ],
            [
                [
                    'metadata' => [
                        'model' => 'App\Model\Metadata',
                    ],
                    'menu' => [
                        'model' => 'App\Model\MenuNode'
                    ],
                    'types' => [],

                ],
                'The child node "node" at path "tuna_cms_node.types" must be configured.',
            ],
            [
                [
                    'metadata' => [
                        'model' => 'App\Model\Metadata',
                    ],
                    'menu' => [
                        'model' => 'App\Model\MenuNode'
                    ],
                    'types' => [
                        'page' => [
                            'model' => '',
                        ],
                    ],
                ],
                'The path "tuna_cms_node.types.page.model" cannot contain an empty value, but got "".',
            ],
            [
                [
                    'metadata' => [
                        'model' => 'App\Model\Metadata',
                    ],
                    'menu' => [
                        'model' => 'App\Model\MenuNode'
                    ],
                    'types' => [
                        'page' => [
                            'model' => 'App\Model\Page',
                            'form' => '',
                        ],
                    ],
                ],
                'The path "tuna_cms_node.types.page.form" cannot contain an empty value, but got "".',
            ],
            [
                [
                    'metadata' => [
                        'model' => 'App\Model\Metadata',
                    ],
                    'menu' => [
                        'model' => 'App\Model\MenuNode'
                    ],
                    'types' => [
                        'page' => [
                            'model' => 'App\Model\Page',
                            'templates' => [
                                'edit' => '',
                            ],
                        ],
                    ],
                ],
                'The path "tuna_cms_node.types.page.templates.edit" cannot contain an empty value, but got "".',
            ],
            [
                [
                    'metadata' => [
                        'model' => 'App\Model\Metadata',
                    ],
                    'menu' => [
                        'model' => 'App\Model\MenuNode'
                    ],
                    'types' => [
                        'page' => [
                            'model' => 'App\Model\Page',
                            'templates' => [
                                'node_item' => '',
                            ],
                        ],
                    ],
                ],
                'The path "tuna_cms_node.types.page.templates.node_item" cannot contain an empty value, but got "".',
            ],
        ];
    }
}
