<?php

namespace TheCodeine\FileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class UploadedFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', Type\FileType::class, array(
            'error_bubbling' => true,
            'constraints' => array(
                new Constraints\File(array(
                    'maxSize' => ini_get('upload_max_filesize'),
                ))
            )
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'tuna_admin',
            'csrf_protection' => false,
        ));
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
