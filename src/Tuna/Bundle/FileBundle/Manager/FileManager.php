<?php

namespace TunaCMS\Bundle\FileBundle\Manager;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File as HttpFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use TunaCMS\Bundle\FileBundle\Entity\AbstractFile;
use TunaCMS\Bundle\FileBundle\Entity\File as TunaFile;
use TunaCMS\Bundle\FileBundle\Entity\Image as TunaImage;

class FileManager
{
    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * @var array
     */
    private $config;

    /**
     * FileManager constructor.
     *
     * @param Filesystem $fs
     * @param array $config
     */
    public function __construct(Filesystem $fs, array $config)
    {
        $this->fs = $fs;
        $this->config = $config;
    }

    /**
     * @param $file
     */
    public function removeFile($file)
    {
        if ($file == null) {
            return;
        }
        $path = $this->getFullFilePath($file);
        $this->fs->remove($path);
    }

    /**
     * @param AbstractFile $file
     *
     * @return bool
     */
    public function fileExists(AbstractFile $file)
    {
        $fullPath = $this->getFullPath($file->isUploaded() ? 'tmp' : 'upload_files', $file->getPath());

        return $this->fs->exists($fullPath);
    }

    /**
     * @param AbstractFile $file
     */
    public function moveTmpFile(AbstractFile $file)
    {
        $filename = basename($file->getPath());
        $from = $this->getFullTmpPath($filename);
        $to = $this->getFullFilePath($filename);

        try {
            $this->fs->copy($from, $to);
        } catch (FileNotFoundException $e) {
            throw $e;
        }
        $this->fs->remove($from);
    }

    /**
     * @param HttpFile $file
     * @return string
     */
    public function generateTmpFilename(HttpFile $file)
    {
        return sprintf('%s.%s', md5(uniqid()), $file->guessExtension());
    }

    /**
     * @param HttpFile $file
     *
     * @return AbstractFile
     */
    public function generateFile(HttpFile $file)
    {
        return $this->generateAbstractFile($file, new TunaFile());
    }

    /**
     * @param HttpFile $file
     *
     * @return AbstractFile
     */
    public function generateImage(HttpFile $file)
    {
        return $this->generateAbstractFile($file, new TunaImage());
    }

    /**
     * @param HttpFile $file
     * @param AbstractFile $tunaFile
     *
     * @return AbstractFile
     */
    private function generateAbstractFile(HttpFile $file, AbstractFile $tunaFile)
    {
        $filename = $this->generateTmpFilename($file);
        $this->fs->copy($file->getRealPath(), $this->getFullTmpPath($filename));

        return $tunaFile->setPath($filename);
    }

    /**
     * @param UploadedFile $file
     * @param $filename
     */
    public function moveUploadedFile(UploadedFile $file, $filename)
    {
        $file->move(
            sprintf('%s/%s', $this->config['web_root_dir'], $this->config['tmp_path']),
            $filename
        );
    }

    public function getFileInfo(AbstractFile $file)
    {
        $path = $this->getFullTmpPath($file->getPath());

        return [
            'mime' => finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path),
            'size' => filesize($path)
        ];
    }

    /**
     * @param $path
     *
     * @return string
     */
    private function getFullTmpPath($path)
    {
        return $this->getFullPath('tmp', $path);
    }

    /**
     * @param $path
     *
     * @return string
     */
    private function getFullFilePath($path)
    {
        return $this->getFullPath('upload_files', $path);
    }

    /**
     * @param $type
     * @param $path
     *
     * @return string
     */
    private function getFullPath($type, $path)
    {
        return sprintf('%s/%s/%s', $this->config['web_root_dir'], $this->config[$type . '_path'], $path);
    }
}
