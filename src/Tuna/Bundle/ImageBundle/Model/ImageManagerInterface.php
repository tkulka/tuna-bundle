<?php

namespace TheCodeine\ImageBundle\Model;

use Symfony\Component\HttpFoundation\File\File;

interface ImageManagerInterface
{
    /**
     * @param File $file
     * @return string Relative path to the file
     */
    public function store(File $file);

    /**
     * Converts relative path to web path accesible by the browser
     *
     * @param string $filePath
     * @return string
     */
    public function filePathToWebPath($filePath);

    /**
     * @param string $webPath
     */
    public function webPathToFilePath($webPath);
}