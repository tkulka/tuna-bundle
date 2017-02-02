<?php

namespace TheCodeine\GalleryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheCodeine\GalleryBundle\Entity\Gallery;
use TheCodeine\GalleryBundle\Entity\GalleryItem;

class GalleryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('items', CollectionType::class, [
                'entry_type' => GalleryItemType::class,
                'required' => false,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'error_bubbling' => false,
                'entry_options' => [
                    'data_class' => GalleryItem::class,
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gallery::class,
            'translation_domain' => 'tuna_admin',
            'attr' => [
                'types' => GalleryItem::$TYPES,
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'thecodeine_gallerybundle_gallery';
    }
}
