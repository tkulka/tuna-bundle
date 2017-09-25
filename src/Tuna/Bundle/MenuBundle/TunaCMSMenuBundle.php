<?php

namespace TunaCMS\Bundle\MenuBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TunaCMS\Bundle\MenuBundle\DependencyInjection\Compiler\MenuTypesPass;

class TunaCMSMenuBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new MenuTypesPass());
    }
}
