<?php

namespace TheCodeine\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TheCodeine\FileBundle\Validator\Constraints as FileAssert;

/**
 * @ORM\EntityListeners({"TheCodeine\FileBundle\EventListener\FileListener"})
 * @ORM\MappedSuperclass
 * @FileAssert\FileExists
 */
abstract class AbstractFile
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @var string Path of persisted file in database (unmapped property)
     */
    protected $persistedPath;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="mime", type="text", nullable=true)
     */
    protected $mime;

    /**
     * @var integer
     * @ORM\Column(name="filesize", type="integer", nullable=true)
     */
    protected $filesize;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return AbstractFile
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param $filename
     *
     * @return AbstractFile
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return AbstractFile
     */
    public function savePersistedPath()
    {
        if ($this->getPath()) {
            $this->persistedPath = $this->path;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getPersistedPath()
    {
        return $this->persistedPath;
    }

    /**
     * @return bool
     */
    public function isUploaded()
    {
        return $this->persistedPath != $this->path;
    }

    /**
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * @return $this
     * @param string $mime
     */
    public function setMime($mime)
    {
        $this->mime = $mime;

        return $this;
    }

    /**
     * @return int
     */
    public function getFilesize()
    {
        return $this->filesize;
    }

    /**
     * @return $this
     * @param int $filesize
     */
    public function setFilesize($filesize)
    {
        $this->filesize = $filesize;

        return $this;
    }
}
