<?php

namespace TheCodeine\ImageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageFile extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'foo' => 'bar'
        ));
    }

    public function getParent()
    {
        return 'file';
    }

    public function getName()
    {
        return 'imagefile';
    }

}