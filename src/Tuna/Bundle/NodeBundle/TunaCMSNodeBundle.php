<?php

namespace TunaCMS\Bundle\NodeBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TunaCMS\Bundle\NodeBundle\DependencyInjection\Compiler\NodeTypesPass;

class TunaCMSNodeBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new NodeTypesPass());
    }
}
