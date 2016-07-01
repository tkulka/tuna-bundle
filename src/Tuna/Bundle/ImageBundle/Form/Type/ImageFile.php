<?php

namespace TheCodeine\ImageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

class ImageFile extends AbstractType
{
    public function getParent()
    {
        return 'file';
    }

    public function getName()
    {
        return 'imagefile';
    }
}
