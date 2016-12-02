<?php

namespace TheCodeine\FileBundle\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheCodeine\FileBundle\Entity\Image;

class ImageType extends AbstractFileType
{
    protected static $DROPZONE_DEFAULTS = [
        'clickable' => '.btn-main-image',
        'maxFiles' => 1,
        'acceptedFiles' => '.png,.jpg,.gif,.jpeg'
    ];

    protected function getEntityClass()
    {
        return Image::class;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        $view->vars['scale_preview_thumbnail'] = $options['scale_preview_thumbnail'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('label', 'Choose image')
            ->setDefault('dropzone_options', self::$DROPZONE_DEFAULTS)
            ->setDefault('scale_preview_thumbnail', true);
    }

    public function getBlockPrefix()
    {
        return 'tuna_image';
    }
}
