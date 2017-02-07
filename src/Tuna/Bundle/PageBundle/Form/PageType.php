<?php

namespace TheCodeine\PageBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use TheCodeine\PageBundle\Entity\Page;

class PageType extends AbstractPageType
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return Page::class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }
}