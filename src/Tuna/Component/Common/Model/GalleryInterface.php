<?php

namespace TunaCMS\Component\Common\Model;

use TunaCMS\Bundle\GalleryBundle\Entity\Gallery;

interface GalleryInterface
{
    /**
     * @param Gallery $gallery
     *
     * @return self
     */
    public function setGallery(Gallery $gallery);

    /**
     * @return Gallery
     */
    public function getGallery();
}
