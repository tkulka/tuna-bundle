<?php

namespace TheCodeine\ImageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ImageFile extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FileType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tuna_image_file';
    }
}