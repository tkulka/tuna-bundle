<?php

namespace TheCodeine\NewsBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use TheCodeine\GalleryBundle\Form\GalleryType;
use TheCodeine\NewsBundle\Entity\News;
use TheCodeine\ImageBundle\Form\ImageRequestThumbnailType;
use TheCodeine\PageBundle\Form\PageType;
use TheCodeine\TagBundle\Form\TagCollectionType;
use TheCodeine\TagBundle\Doctrine\TagManager;

class NewsType extends PageType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('important', null, array(
                'required' => false
            ))
            ->add('tags', 'tag_collection', array(
                'required' => false
            ))
            ->add('createdAt', 'datetime', array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'required' => false
            ));
    }

    protected function getTranslatableClass()
    {
        return 'TheCodeine\NewsBundle\Entity\News';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TheCodeine\NewsBundle\Entity\News',
            'em' => null,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'thecodeine_newsbundle_news';
    }
}
