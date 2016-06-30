<?php

namespace TheCodeine\ImageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
