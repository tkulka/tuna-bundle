<?php

namespace TunaCMS\Bundle\MenuBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use TunaCMS\Bundle\MenuBundle\DependencyInjection\TunaCMSMenuExtension;

class TunaCMSMenuExtensionTest extends TestCase
{
    public function testLoadConfiguration()
    {
        $configuration = new ContainerBuilder();
        $loader = new TunaCMSMenuExtension();
        $loader->load(
            [
                'tuna_cms_menu' => [
                    'model' => 'Custom\Model\Custom',
                ],
            ],
            $configuration
        );

        $parameterBag = $configuration->getParameterBag();

        $this->assertEquals(
            [
                'tuna_cms_menu.model' => 'Custom\Model\Custom',
                'tuna_cms_menu.form' => 'TunaCMS\Bundle\MenuBundle\Form\MenuType',
                'tuna_cms_menu.manager_class' => 'TunaCMS\Bundle\MenuBundle\Service\MenuManager',
            ],
            $parameterBag->all()
        );
    }
}
