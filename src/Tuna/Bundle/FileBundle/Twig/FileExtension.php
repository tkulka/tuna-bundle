<?php

namespace TunaCMS\Bundle\FileBundle\Twig;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Asset\Packages;
use TunaCMS\Bundle\FileBundle\Entity\AbstractFile;

class FileExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    private $paths;

    /**
     * @var Packages
     */
    private $packages;

    /**
     * @var CacheManager
     */
    private $imagine;

    /**
     * FileExtension constructor.
     *
     * @param $paths
     * @param Packages $packages
     * @param CacheManager $imagine
     */
    public function __construct($paths, Packages $packages, CacheManager $imagine)
    {
        $this->paths = $paths;
        $this->packages = $packages;
        $this->imagine = $imagine;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('tuna_uploadDir', [$this, 'getUploadDir']),
            new \Twig_SimpleFunction('tuna_file', [$this, 'getFileWebPath']),
            new \Twig_SimpleFunction('tuna_image', [$this, 'getImageWebPath']),
        ];
    }

    /**
     * @param AbstractFile|null $file
     *
     * @return string
     */
    public function getFileWebPath(AbstractFile $file = null)
    {
        if (!$file) {
            return '';
        }
        return $this->packages->getUrl(sprintf('%s/%s',
            $this->getUploadDir($file->isUploaded() ? 'tmp_path' : 'upload_files_path'),
            $file->getPath()
        ));
    }

    /**
     * @param AbstractFile|null $file
     * @param null $filter
     *
     * @return string
     */
    public function getImageWebPath(AbstractFile $file = null, $filter = null)
    {
        if (!$file) {
            return '';
        }

        $webPath = $this->getFileWebPath($file);

        return $filter ? $this->imagine->getBrowserPath($webPath, $filter) : $webPath;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function getUploadDir($name)
    {
        if (array_key_exists($name, $this->paths)) {
            return $this->paths[$name];
        } else {
            throw new \InvalidArgumentException(sprintf('Upload path "%s" is not defined.', $name));
        }
    }
}
