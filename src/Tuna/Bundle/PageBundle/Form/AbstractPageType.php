<?php

namespace TheCodeine\PageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

use TheCodeine\ImageBundle\Form\MainImage;
use TheCodeine\NewsBundle\Entity\Page;
use TheCodeine\NewsBundle\Form\AttachmentType;
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
            ->add('image', new MainImage($options['data']->getImage() !== null), array(
                'required' => false,
            ))
            ->add('published', 'checkbox', array(
                'required' => false
            ))
            ->add('attachments', 'collection', array(
                'type' => new AttachmentType(),
                'required' => false,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true
            ))
            ->add('gallery', new GalleryType(), array(
                'data_class' => 'TheCodeine\GalleryBundle\Entity\Gallery'
            ))
            ->add('translations', 'a2lix_translations_gedmo', array(
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
            ))
            ->add('save', 'submit');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->getEntityClass(),
            'cascade_validation' => true,
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
