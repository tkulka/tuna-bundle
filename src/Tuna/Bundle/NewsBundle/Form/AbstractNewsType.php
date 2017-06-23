<?php

namespace TheCodeine\NewsBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\GedmoTranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheCodeine\FileBundle\Form\AttachmentCollectionType;
use TheCodeine\FileBundle\Form\MainImageType;
use TheCodeine\GalleryBundle\Form\GalleryType;
use TunaCMS\EditorBundle\Form\EditorType;

abstract class AbstractNewsType extends AbstractType
{
    /**
     * @return string
     */
    abstract protected function getEntityClass();


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', MainImageType::class, [
                'label' => 'ui.form.labels.image.main'
            ])
            ->add('published', CheckboxType::class, [
                'required' => false,
                'label' => 'ui.form.labels.published'
            ])
            ->add('attachments', AttachmentCollectionType::class)
            ->add('gallery', GalleryType::class)
            ->add('translations', GedmoTranslationsType::class, [
                'translatable_class' => $this->getEntityClass(),
                'fields' => [
                    'title' => [
                        'required' => true,
                        'label' => 'ui.form.labels.title'
                    ],
                    'teaser' => [
                        'field_type' => EditorType::class,
                        'config_name' => 'tuna.editor.config.simple',
                        'attr' => [
                            'data-type' => 'basic'
                        ],
                        'required' => false,
                        'label' => 'ui.form.labels.teaser'
                    ],
                    'body' => [
                        'field_type' => EditorType::class,
                        'required' => true,
                        'label' => 'ui.form.labels.body'
                    ],
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->getEntityClass(),
            'translation_domain' => 'tuna_admin',
        ]);
    }
}
