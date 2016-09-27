<?php

namespace TheCodeine\CategoryBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\GedmoTranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CategoryType extends AbstractType
{
    public function __construct($dataClass = 'TheCodeine\CategoryBundle\Entity\Category')
    {
        $this->dataClass = $dataClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', GedmoTranslationsType::class, array(
                'translatable_class' => $this->dataClass,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass,
            'translation_domain' => 'tuna_admin',
            'error_bubbling' => false
        ));
    }

    public function getName()
    {
        return 'tuna_category';
    }
}
