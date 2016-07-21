<?php

namespace TheCodeine\FileBundle\Form;

use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;
use TheCodeine\FileBundle\Entity\Image;

class ImageType extends FileType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Image::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'tuna_image';
    }
}
