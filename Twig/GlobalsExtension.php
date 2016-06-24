<?php

namespace TheCodeine\AdminBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;


class GlobalsExtension extends \Twig_Extension
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('tuna_getPath', function($name) {
                return $this->container->getParameter($name);
            })
        );
    }

    public function getName()
    {
        return 'thecodeine_admin_globals_extension';
    }
}
