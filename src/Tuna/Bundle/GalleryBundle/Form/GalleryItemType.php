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
            ->add('type', 'hidden');

        $formModifier = function (FormInterface $form, $type) {
            if (empty($type)) {
                return;
            }

            if ($type === GalleryItem::VIDEO_TYPE) {
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
                                'field_type' => 'text',
                                'label' => false,
                                'attr' => array(
                                    'placeholder' => 'Video title',
                                    'class' => 'form-control'
                                )
                            ),
                        )
                    ));
            } else if ($type === GalleryItem::IMAGE_TYPE) {
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
                                    'placeholder' => 'Image title',
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
