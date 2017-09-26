<?php

namespace TunaCMS\Component\Common\Model;

use TunaCMS\Bundle\FileBundle\Entity\Image;

interface ImageInterface
{
    /**
     * @param Image $image
     *
     * @return self
     */
    public function setImage(Image $image);

    /**
     * @return Image
     */
    public function getImage();
}
