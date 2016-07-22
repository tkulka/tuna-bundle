<?php

namespace TheCodeine\FileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;
use TheCodeine\FileBundle\Entity\File;

abstract class AbstractFileType extends AbstractType
{
    /**
     * @return string Fully qualified class name of
     */
    abstract protected function getEntityClass();

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path')
            ->add('filename');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->getEntityClass(),
            'error_bubbling' => false
        ));
    }

    public function getBlockPrefix()
    {
        return 'tuna_file';
    }
}
