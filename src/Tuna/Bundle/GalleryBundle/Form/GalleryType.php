<?php

namespace TheCodeine\GalleryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheCodeine\GalleryBundle\Entity\GalleryItem;

class GalleryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('items', CollectionType::class, array(
                'type' => new GalleryItemType(),
                'required' => false,
                'allow_add' => true,
                'prototype' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'options' => array(
                    'data_class' => 'TheCodeine\GalleryBundle\Entity\GalleryItem'
                )
            ))
            ->add('save', 'submit');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TheCodeine\GalleryBundle\Entity\Gallery',
            'attr' => array(
                'types' => GalleryItem::$TYPES,
            ),
            'cascade_validation' => true,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'thecodeine_gallerybundle_gallery';
    }
}
