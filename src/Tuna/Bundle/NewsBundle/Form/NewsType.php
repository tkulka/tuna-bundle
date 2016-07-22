<?php

namespace TheCodeine\NewsBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use TheCodeine\PageBundle\Form\AbstractPageType;
use TheCodeine\TagBundle\Form\TagCollectionType;

class NewsType extends AbstractPageType
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
            ->add('tags', TagCollectionType::class, array(
                'required' => false
            ))
            ->add('createdAt', DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'required' => false
            ));
    }

    protected function getEntityClass()
    {
        return 'TheCodeine\NewsBundle\Entity\News';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('data_class', 'TheCodeine\NewsBundle\Entity\AbstractNews');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'thecodeine_newsbundle_news';
    }
}
