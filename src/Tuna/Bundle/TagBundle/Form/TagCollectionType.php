<?php

namespace TheCodeine\TagBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use TheCodeine\TagBundle\Form\DataTransformer\TextToTagArrayCollectionTransformer;
use TheCodeine\TagBundle\Doctrine\TagManagerInterface;

class TagCollectionType extends AbstractType
{
    /**
     * @var TagManagerInterface
     */
    private $tagManager;

    /**
     * TagCollectionType constructor.
     *
     * @param TagManagerInterface $tagManager
     */
    public function __construct(TagManagerInterface $tagManager)
    {
        $this->tagManager = $tagManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new TextToTagArrayCollectionTransformer($this->tagManager));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'tuna_admin',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tuna_tag';
    }
}