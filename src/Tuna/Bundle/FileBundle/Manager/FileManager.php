<?php

namespace TheCodeine\FileBundle\Manager;

use Symfony\Component\Filesystem\Filesystem;

class FileManager
{
    /**
     * @var Filesystem
     */
    private $fs;

    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs;
    }
}
