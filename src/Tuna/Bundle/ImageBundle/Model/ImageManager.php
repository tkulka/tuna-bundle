<?php

namespace TheCodeine\ImageBundle\Model;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

use Gaufrette\Filesystem;

class ImageManager implements ImageManagerInterface
{
    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * @var string
     */
    private $basePath;

    /**
     * @var Request
     */
    private $request;

    /**
     * @param Filesystem $fs
     * @param $basePath
     */
    public function __construct(Filesystem $fs, $basePath)
    {
        $this->fs = $fs;
        $this->basePath = $basePath;
    }

    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    public function store(File $file)
    {
        $filePath = $this->randomFilepath($file);

        $this->fs->write($filePath, file_get_contents($file->getPathname()));

        return $filePath;
    }

    public function filePathToWebPath($filePath, $absolute = false)
    {
        return sprintf('%s%s%s', $absolute ? $this->request->getSchemeAndHttpHost() : '', $this->basePath, $filePath);
    }

    public function webPathToFilePath($webPath)
    {
        if (0 !== strpos($webPath, $this->basePath)) {
            throw new \RuntimeException("Unknown web path `$webPath`");
        }

        return substr($webPath, strlen($this->basePath));
    }

    private function randomDirectory()
    {
        return sprintf("%s%s/%s%s",
            chr(97 + mt_rand(0, 25)),
            chr(97 + mt_rand(0, 25)),
            chr(97 + mt_rand(0, 25)),
            chr(97 + mt_rand(0, 25))
        );
    }

    private function randomFilepath(File $file)
    {
        $directory = $this->randomDirectory();
        $extension = $this->getFileExtension($file);
        $hashKey = $directory . $file->getFilename() . $file->getMTime();

        do {
            $filePath = sprintf('%s%s%s.%s', $directory, '/', md5($hashKey), $extension);
        } while ($this->fs->has($filePath));

        return $filePath;
    }

    private function getFileExtension(File $file)
    {

        if ($file instanceof UploadedFile) {
            $extension = $file->getClientOriginalExtension();
            if (!empty($extension)) {
                return strtolower($extension);
            }
        } else if ($file instanceof File) {
            $extension = $file->getExtension();
            if (!empty($extension)) {
                return strtolower($extension);
            }
        }

        if (empty($extension)) {
            $extension = $file->guessExtension();
            if (!empty($extension)) {
                return strtolower($extension);
            }
        }

        throw new \RuntimeException("Cannot get file extension!");
    }
}