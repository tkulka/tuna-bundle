<?php

namespace TunaCMS\Bundle\VideoBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use TunaCMS\Bundle\VideoBundle\DependencyInjection\TunaCMSVideoExtension;

class TunaCMSVideoExtensionTest extends TestCase
{
    public function testLoadConfiguration()
    {
        $configuration = new ContainerBuilder();
        $loader = new TunaCMSVideoExtension();
        $loader->load([], $configuration);

        $parameterBag = $configuration->getParameterBag();

        $this->assertEquals(
            [
                'tuna_cms_video.manager_class' => 'TunaCMS\Bundle\VideoBundle\Doctrine\VideoManager',
                'tuna_cms_video.form_type_class' => 'TunaCMS\Bundle\VideoBundle\Form\VideoUrlType',
                'tuna_cms_video.entity_manager_class' => 'Doctrine\ORM\EntityManager',
                'tuna_cms_video.twig_extension_class' => 'TunaCMS\Bundle\VideoBundle\Twig\Extension\VideoPlayerExtension',
            ],
            $parameterBag->all()
        );
    }
}
