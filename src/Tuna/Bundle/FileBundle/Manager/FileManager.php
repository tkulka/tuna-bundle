<?php

namespace TheCodeine\FileBundle\Manager;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File as HttpFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use TheCodeine\FileBundle\Entity\AbstractFile;

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

    public function __construct(Filesystem $fs, array $config)
    {
        $this->fs = $fs;
        $this->config = $config;
    }

    public function removeFile(AbstractFile $file)
    {
        dump($file);
        $this->fs->remove($file->getOldPath());
    }

    public function generateTmpFilename(HttpFile $file)
    {
        return sprintf(
            '%s.%s',
            md5(uniqid()),
            $file->guessExtension()
        );
    }

    public function getTmpPath($filename)
    {
        return $filename;
    }

    public function moveUploadedFile(UploadedFile $file, $filename)
    {
        $file->move(
            sprintf(
                '%s/%s',
                $this->config['web_root_dir'],
                $this->config['tmp_files_path']
            ),
            $filename
        );
    }

    public function moveTmpFile(AbstractFile $file)
    {
        $filename = basename($file->getPath());
        $from = sprintf(
            '%s/%s/%s',
            $this->config['web_root_dir'],
            $this->config['tmp_files_path'],
            $filename
        );
        $to = sprintf(
            '%s/%s/%s',
            $this->config['web_root_dir'],
            $this->config['files_path'],
            $filename
        );

        $this->fs->copy($from, $to);
        $this->fs->remove($from);
    }
}
