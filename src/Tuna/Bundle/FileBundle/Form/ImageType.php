<?php

namespace TheCodeine\FileBundle\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheCodeine\FileBundle\Entity\Image;

class ImageType extends AbstractFileType
{
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
        $resolver->setDefault('accepted_files', '.png,.jpg,.gif,.jpeg');
        $resolver->setDefault('scale_preview_thumbnail', true);
    }


    public function getBlockPrefix()
    {
        return 'tuna_image';
    }
}
