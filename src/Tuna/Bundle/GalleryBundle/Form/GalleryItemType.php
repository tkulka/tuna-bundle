<?php

namespace TheCodeine\GalleryBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\GedmoTranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

use TheCodeine\FileBundle\Form\ImageType;
use TheCodeine\GalleryBundle\Entity\GalleryItem;
use TheCodeine\VideoBundle\Form\VideoUrlType;

class GalleryItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', HiddenType::class);

        $formModifier = function (FormInterface $form, $type) {
            if (empty($type)) return;

            if ($type === GalleryItem::VIDEO_TYPE) {
                $form
                    ->add('position', HiddenType::class)
                    ->add('video', VideoUrlType::class, [
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'ui.form.labels.video.url',
                        ],
                    ])
                    ->add('translations', GedmoTranslationsType::class, [
                        'translatable_class' => GalleryItem::class,
                        'fields' => [
                            'name' => [
                                'field_type' => 'text',
                                'label' => false,
                                'attr' => [
                                    'placeholder' => 'ui.form.labels.video.title',
                                ],
                            ],
                        ],
                    ]);
            } else if ($type === GalleryItem::IMAGE_TYPE) {
                $form
                    ->add('position', HiddenType::class)
                    ->add('image', ImageType::class, [
                        'label' => false,
                        'init_dropzone' => false,
                        'attr' => [
                            'deletable' => false,
                        ],
                    ])
                    ->add('translations', GedmoTranslationsType::class, [
                        'translatable_class' => GalleryItem::class,
                        'fields' => [
                            'name' => [
                                'label' => false,
                                'attr' => [
                                    'placeholder' => 'ui.form.labels.image.title',
                                ],
                            ],
                        ],
                    ]);
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
     * {@inheritdoc}
     */
    public function setOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GalleryItem::class,
            'error_bubbling' => false,
            'translation_domain' => 'tuna_admin',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'thecodeine_gallerybundle_item';
    }
}
