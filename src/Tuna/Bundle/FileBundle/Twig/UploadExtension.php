<?php

namespace TheCodeine\FileBundle\Twig;

class UploadExtension extends \Twig_Extension
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
            new \Twig_SimpleFunction('tuna_uploadDir', function ($name) {
                if (array_key_exists($name, $this->paths)) {
                    return $this->paths[$name];
                } else {
                    throw new \InvalidArgumentException(sprintf(
                        'Upload path "%s" is not defined.',
                        $name
                    ));
                }
            })
        );
    }

    public function getName()
    {
        return 'thecodeine_file_extension';
    }
}
