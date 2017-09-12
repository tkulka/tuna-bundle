<?php

namespace TunaCMS\Bundle\FileBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use TunaCMS\Bundle\FileBundle\DependencyInjection\TunaCMSFileExtension;

class TunaCMSFileExtensionTest extends TestCase
{
    public function testLoadDefaultConfiguration()
    {
        $configuration = new ContainerBuilder();
        $loader = new TunaCMSFileExtension();
        $loader->load([], $configuration);

        $parameterBag = $configuration->getParameterBag();

        $this->assertTrue($parameterBag->has('tuna_cms_file.file_manager'));
    }
}
