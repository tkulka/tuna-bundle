<?php

namespace TheCodeine\ImageBundle\Form;


use Symfony\Component\Form\FormBuilderInterface;
use TheCodeine\ImageBundle\Form\Type\MainImageFile;

class MainImage extends ImageRequestThumbnailType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('file', new MainImageFile(), array(
            'label' => false,
        ));
    }

    public function getName()
    {
        return 'main_image';
    }
}
