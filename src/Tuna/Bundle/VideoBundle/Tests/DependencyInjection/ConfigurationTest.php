<?php

namespace TunaCMS\Bundle\VideoBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use TunaCMS\Bundle\VideoBundle\DependencyInjection\Configuration;

class ConfigurationTest extends TestCase
{
    public function testDefaultConfiguration()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), []);

        $this->assertEquals(
            [
                'manager_class' => 'TunaCMS\Bundle\VideoBundle\Doctrine\VideoManager',
                'form_type_class' => 'TunaCMS\Bundle\VideoBundle\Form\VideoUrlType',
                'entity_manager_class' => 'Doctrine\ORM\EntityManager',
                'twig_extension_class' => 'TunaCMS\Bundle\VideoBundle\Twig\Extension\VideoPlayerExtension',
            ],
            $config
        );
    }
}
