<?php

namespace TunaCMS\Bundle\MenuBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use TunaCMS\Bundle\MenuBundle\DependencyInjection\Configuration;

class ConfigurationTest extends TestCase
{
    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The child node "model" at path "tuna_cms_menu" must be configured.
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
                    'model' => 'Custom\Model\Custom',
                ],
                [
                    'model' => 'Custom\Model\Custom',
                    'form' => 'TunaCMS\Bundle\MenuBundle\Form\MenuType',
                    'manager_class' => 'TunaCMS\Bundle\MenuBundle\Service\MenuManager',
                ],
            ],
            [
                [
                    'model' => 'Custom\Model\Custom',
                    'form' => 'Custom\Form\CustomMenuType',
                    'manager_class' => 'Custom\Service\CustomMenuManager',
                ],
                [
                    'model' => 'Custom\Model\Custom',
                    'form' => 'Custom\Form\CustomMenuType',
                    'manager_class' => 'Custom\Service\CustomMenuManager',
                ],
            ],
        ];
    }

}
