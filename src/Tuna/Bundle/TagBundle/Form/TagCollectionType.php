<?php

namespace TheCodeine\TagBundle\Form;

use TheCodeine\TagBundle\Model\TagManagerInterface;

use TheCodeine\TagBundle\Form\DataTransformer\TextToTagArrayCollectionTransformer;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class TagCollectionType extends AbstractType
{
    /**
     * Tag manager
     *
     * @var TagManagerInterface
     */
    private $tagManager;

    public function __construct(TagManagerInterface $tagManager)
    {
        $this->tagManager = $tagManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new TextToTagArrayCollectionTransformer($this->tagManager));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'tag_collection';
    }
}