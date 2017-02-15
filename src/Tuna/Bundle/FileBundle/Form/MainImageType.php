<?php

namespace TheCodeine\FileBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;

class MainImageType extends ImageType
{
    public static function getDropzoneDefaultOptions()
    {
        return array_merge(
            parent::getDropzoneDefaultOptions(),
            [
                'selector' => '.admin-option-container',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('label', 'ui.form.labels.image.main');
    }
}
