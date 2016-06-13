<?php

namespace TheCodeine\ImageBundle\Model;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class Image
{
    /**
     * "Virtual" entity property used to handle image upload only
     *
     * @var File
     *
     * @Assert\Image(mimeTypes={"image/jpeg", "image/png"})
     */
    protected $file;

    /**
     * "Virtual" entity property used to handle image upload from URL only
     *
     * @var string
     *
     * @Assert\Url()
     */
    protected $url;

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File $file
     * @return \TheCodeine\ImageBundle\Model\Image
     */
    public function setFile(File $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return \TheCodeine\ImageBundle\Model\Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
}