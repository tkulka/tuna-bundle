<?php

namespace TheCodeine\FileBundle\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheCodeine\FileBundle\Entity\Image;

class ImageType extends AbstractFileType
{
    const MAX_FILESIZE = 10;
    const ACCEPTED_FILES = '.png,.jpg,.gif,.jpeg';

    public static function getDropzoneDefaultOptions()
    {
        return array_merge(
            parent::getDropzoneDefaultOptions(),
            [
                'clickable' => '.btn-main-image',
                'maxFiles' => 1,
                'maxFilesize' => self::MAX_FILESIZE,
                'acceptedFiles' => self::ACCEPTED_FILES,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getEntityClass()
    {
        return Image::class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        $view->vars['scale_preview_thumbnail'] = $options['scale_preview_thumbnail'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('label', 'Choose image')
            ->setDefault('scale_preview_thumbnail', true);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tuna_image';
    }
}
