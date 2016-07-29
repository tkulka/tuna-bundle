<?php

namespace TheCodeine\FileBundle\Form;

use TheCodeine\FileBundle\Entity\Image;

class ImageType extends AbstractFileType
{
    protected function getEntityClass()
    {
        return Image::class;
    }

    public function getBlockPrefix()
    {
        return 'tuna_image';
    }
}
