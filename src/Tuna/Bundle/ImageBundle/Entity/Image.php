<?php

namespace TheCodeine\ImageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use TheCodeine\ImageBundle\Model\Image as BaseImage;


/**
 * Image
 *
 * @ORM\Table(name="images")
 * @ORM\Entity
 */
class Image extends BaseImage
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="path", type="string")
     */
    private $path;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime $updatedAt
     */
    protected $updatedAt;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param File $file
     *
     * @return Image
     */
    public function setFile(File $file)
    {
        $this->file = $file;

        if ($this->file) {
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @param string $url
     *
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        if ($this->url) {
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }
}