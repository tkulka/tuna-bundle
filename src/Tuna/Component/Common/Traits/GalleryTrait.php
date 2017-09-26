<?php

namespace TunaCMS\Component\Common\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use TunaCMS\Bundle\GalleryBundle\Entity\Gallery;

trait GalleryTrait
{
    /**
     * @var Gallery
     *
     * @ORM\OneToOne(targetEntity="TunaCMS\Bundle\GalleryBundle\Entity\Gallery", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="gallery_id", referencedColumnName="id")
     *
     * @Assert\Valid
     */
    protected $gallery;

    /**
     * @inheritdoc
     */
    public function setGallery(Gallery $gallery)
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getGallery()
    {
        return $this->gallery;
    }
}
