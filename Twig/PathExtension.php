<?php

namespace TheCodeine\AdminBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;


class PathExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    private $paths;

    public function __construct($paths)
    {
        $this->paths = $paths;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('tuna_getPath', function ($name) {
                if (array_key_exists($name, $this->paths)) {
                    return $this->paths[$name];
                } else {
                    throw new \InvalidArgumentException(sprintf(
                        'Path "%s" is not defined. Maybe you forgot to add it to thecodeine_admin.paths config?',
                        $name
                    ));
                }
            })
        );
    }

    public function getName()
    {
        return 'thecodeine_admin_path_extension';
    }
}
