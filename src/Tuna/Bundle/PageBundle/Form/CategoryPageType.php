<?php

namespace TheCodeine\PageBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use TheCodeine\CategoryBundle\Entity\Category;
use TheCodeine\PageBundle\Entity\CategoryPage;

class CategoryPageType extends AbstractPageType
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return CategoryPage::class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'placeholder' => 'Choose category',
                'choice_label' => 'name'
            ]);
    }
}