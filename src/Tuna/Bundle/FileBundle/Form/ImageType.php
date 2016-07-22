<?php

namespace TheCodeine\FileBundle\Form;

use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Validator\Constraints;
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
