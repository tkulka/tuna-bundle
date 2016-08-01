<?php

namespace TheCodeine\FileBundle\Twig;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Cache\Resolver\WebPathResolver;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;
use TheCodeine\FileBundle\Entity\AbstractFile;

class FileExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    private $paths;

    /**
     * @var AssetsHelper
     */
    private $assetsHelper;

    /**
     * @var CacheManager
     */
    private $imagine;

    public function __construct($paths, AssetsHelper $assetsHelper, CacheManager $imagine)
    {
        $this->paths = $paths;
        $this->assetsHelper = $assetsHelper;
        $this->imagine = $imagine;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('tuna_uploadDir', array($this, 'getUploadDir')),
            new \Twig_SimpleFunction('tuna_file', array($this, 'getFileWebPath')),
            new \Twig_SimpleFunction('tuna_image', array($this, 'getImageWebPath')),
        );
    }

    public function getFileWebPath(AbstractFile $file)
    {
        return $this->assetsHelper->getUrl(sprintf('%s/%s',
            $this->getUploadDir($file->isUploaded() ? 'tmp_path' : 'upload_files_path'),
            $file->getPath()
        ));
    }

    public function getImageWebPath(AbstractFile $file, $filter = null)
    {
        $webPath = $this->getFileWebPath($file);

        return $filter ?
            $this->imagine->getBrowserPath($webPath, $filter) :
            $webPath;
    }

    public function getUploadDir($name)
    {
        if (array_key_exists($name, $this->paths)) {
            return $this->paths[$name];
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Upload path "%s" is not defined.',
                $name
            ));
        }
    }

    public function getName()
    {
        return 'thecodeine_file_extension';
    }
}
