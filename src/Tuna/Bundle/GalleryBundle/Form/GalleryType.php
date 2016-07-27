<?php

namespace TheCodeine\GalleryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheCodeine\GalleryBundle\Entity\Gallery;
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
            ->add('items', Type\CollectionType::class, array(
                'entry_type' => GalleryItemType::class,
                'required' => false,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'error_bubbling' => false,
                'entry_options' => array(
                    'data_class' => GalleryItem::class,
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Gallery::class,
            'attr' => array(
                'types' => GalleryItem::$TYPES,
            ),
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
