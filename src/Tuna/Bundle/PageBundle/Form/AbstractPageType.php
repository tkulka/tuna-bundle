<?php

namespace TheCodeine\PageBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\GedmoTranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

use TheCodeine\FileBundle\Form\AttachmentCollectionType;
use TheCodeine\FileBundle\Form\MainImageType;
use TheCodeine\GalleryBundle\Form\GalleryType;
use TunaCMS\EditorBundle\Form\EditorType;

abstract class AbstractPageType extends AbstractType
{
    /**
     * @var bool
     */
    private $validate;

    /**
     * @return string Fully qualified class name of
     */
    abstract protected function getEntityClass();

    /**
     * PageType constructor.
     * @param $validate
     */
    public function __construct($validate = true)
    {
        $this->validate = $validate;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$this->validate) {
            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $event->stopPropagation();
            }, 900);
        }
        $builder
            ->add('image', MainImageType::class)
            ->add('published', CheckboxType::class, [
                'required' => false
            ])
            ->add('attachments', AttachmentCollectionType::class)
            ->add('gallery', GalleryType::class)
            ->add('translations', GedmoTranslationsType::class, [
                'translatable_class' => $this->getEntityClass(),
                'fields' => [
                    'title' => [
                        'required' => true
                    ],
                    'teaser' => [
                        'field_type' => EditorType::class,
                        'config_name' => 'tuna.editor.config.simple',
                        'attr' => [
                            'data-type' => 'basic',
                        ],
                        'required' => false,
                    ],
                    'body' => [
                        'field_type' => EditorType::class,
                        'required' => true
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

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'thecodeine_pagebundle_page';
    }
}
