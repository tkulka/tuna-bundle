<?php

namespace TheCodeine\FileBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;

class MainImageType extends ImageType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('label', 'Choose main image');
        $resolver->setDefault('dropzone_options', ['selector' => '.admin-option-container'] + self::$DROPZONE_DEFAULTS);
    }
}
