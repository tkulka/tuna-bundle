<?php

namespace TheCodeine\NewsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use TheCodeine\NewsBundle\Form\CategoryType;

class CategoryEditType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rows', 'collection', array(
                'type' => new CategoryType(),
                'allow_add' => false,
                'allow_delete' => false,
                'by_reference' => false,
                'prototype' => true
            ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'thecodeine_newsbundle_category_edit';
    }
}
