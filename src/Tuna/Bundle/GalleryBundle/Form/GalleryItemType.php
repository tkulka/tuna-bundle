<?php

namespace TheCodeine\GalleryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

use TheCodeine\GalleryBundle\Entity\GalleryItem;
use TheCodeine\ImageBundle\Form\ImageRequestThumbnailType;

class GalleryItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST')
            ->setAttribute('enctype', 'multipart/form-data')
            ->add('type', 'choice', array(
                'choices' => array(
                    0 => 'video',
                    1 => 'image'
                ),
                'required' => true,
                'attr' => array(
                    'original_widget' => true,
                )
            ));

        $formModifier = function (FormInterface $form, $type) {
            if ($type === null || !is_int($type)) {
                return;
            }

            if ($type === 0) {
                $form
                    ->add('position', 'hidden')
                    ->add('video', 'thecodeine_videobundle_url', array(
                        'attr' => array(
                            'placeholder' => 'Video URL'
                        )
                    ))
                    ->add('translations', 'a2lix_translations_gedmo', array(
                        'translatable_class' => 'TheCodeine\GalleryBundle\Entity\GalleryItem',
                        'fields' => array(
                            'name' => array(
                                'attr' => array(
                                    'placeholder' => 'Video name',
                                    'class' => 'form-control'
                                )
                            ),
                        )
                    ));
            } else if ($type === 1) {
                $form
                    ->add('position', 'hidden')
                    ->add('image', new ImageRequestThumbnailType(), array(
                        'data_class' => 'TheCodeine\ImageBundle\Entity\Image'
                    ))
                    ->add('translations', 'a2lix_translations_gedmo', array(
                        'translatable_class' => 'TheCodeine\GalleryBundle\Entity\GalleryItem',
                        'fields' => array(
                            'name' => array(
                                'attr' => array(
                                    'placeholder' => 'Image name',
                                    'class' => 'form-control'
                                )
                            )
                        )
                    ));
            }
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $item = $event->getData();
                $type = null;

                if ($item instanceof GalleryItem) {
                    $type = $item->getType();
                }

                $formModifier($event->getForm(), $type);
            }
        );

        $builder->get('type')->addEventListener(FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $type = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $type);
            }
        );

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function setOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TheCodeine\GalleryBundle\Entity\GalleryItem'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'thecodeine_gallerybundle_item';
    }
}
