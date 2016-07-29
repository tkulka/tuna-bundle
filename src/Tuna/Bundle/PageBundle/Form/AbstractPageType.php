<?php

namespace TheCodeine\PageBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\GedmoTranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

use TheCodeine\FileBundle\Form\AttachmentCollectionType;
use TheCodeine\FileBundle\Form\ImageType;
use TheCodeine\NewsBundle\Entity\Page;
use TheCodeine\NewsBundle\Form\AttachmentsType;
use TheCodeine\GalleryBundle\Form\GalleryType;

abstract class AbstractPageType extends AbstractType
{
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
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$this->validate) {
            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $event->stopPropagation();
            }, 900);
        }
        $builder
            ->add('image', ImageType::class,  array(
                'label' => false
            ))
            ->add('published', Type\CheckboxType::class, array(
                'required' => false
            ))
            ->add('attachments', AttachmentCollectionType::class)
            ->add('gallery', GalleryType::class)
            ->add('translations', GedmoTranslationsType::class, array(
                'translatable_class' => $this->getEntityClass(),
                'fields' => array(
                    'title' => array(
                        'required' => true
                    ),
                    'teaser' => array(
                        'field_type' => 'editor',
                        'attr' => array(
                            'data-type' => 'basic',
                        ),
                        'required' => false,
                    ),
                    'body' => array(
                        'field_type' => 'editor',
                        'required' => true
                    )
                )
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->getEntityClass(),
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'thecodeine_pagebundle_page';
    }
}
