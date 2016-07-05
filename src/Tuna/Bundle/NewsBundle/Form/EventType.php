<?php

namespace TheCodeine\NewsBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use TheCodeine\GalleryBundle\Form\GalleryType;
use TheCodeine\NewsBundle\Entity\Category;
use TheCodeine\NewsBundle\Entity\News;
use TheCodeine\ImageBundle\Form\ImageRequestThumbnailType;
use TheCodeine\PageBundle\Form\PageType;
use TheCodeine\TagBundle\Form\TagCollectionType;
use TheCodeine\TagBundle\Doctrine\TagManager;

class EventType extends NewsType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('startDate', 'datetime', array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss'
            ))
            ->add('endDate', 'datetime', array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'required' => false
            ));
    }

    protected function getTranslatableClass()
    {
        return 'TheCodeine\NewsBundle\Entity\Event';
    }
}
