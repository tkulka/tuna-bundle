<?php

namespace TheCodeine\PageBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TheCodeine\PageBundle\DependencyInjection\TheCodeinePageExtension;

class TheCodeinePageBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->registerExtension(new TheCodeinePageExtension());
    }

}
