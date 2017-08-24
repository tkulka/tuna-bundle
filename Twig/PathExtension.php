<?php

namespace TunaCMS\AdminBundle\Twig;

class PathExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    private $paths;

    /**
     * PathExtension constructor.
     *
     * @param $paths
     */
    public function __construct($paths)
    {
        $this->paths = $paths;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('tuna_getPath', [$this, 'getPath'])
        ];
    }

    /**
     * @param $name
     *
     * @return string
     */
    public function getPath($name)
    {
        if (array_key_exists($name, $this->paths)) {
            return '/' . $this->paths[$name];
        } else {
            throw new \InvalidArgumentException(sprintf('Path "%s" is not defined. Maybe you forgot to add it to tuna_cms_admin.paths config?', $name));
        }
    }
}
