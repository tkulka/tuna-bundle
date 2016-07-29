<?php

namespace TheCodeine\FileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttachmentCollectionType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'entry_type' => AttachmentType::class,
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'error_bubbling' => false,
        ));
    }

    public function getParent()
    {
        return CollectionType::class;
    }

    public function getName()
    {
        return 'tuna_attachments';
    }
}
