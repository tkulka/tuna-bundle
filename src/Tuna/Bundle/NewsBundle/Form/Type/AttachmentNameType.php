<?php

namespace TheCodeine\NewsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AttachmentNameType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'horizontal_input_wrapper_class' => 'col-lg-12'
        ));
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'attachmentname';
    }

}