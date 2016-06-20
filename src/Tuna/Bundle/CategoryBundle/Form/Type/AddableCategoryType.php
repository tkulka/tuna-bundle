<?php

namespace TheCodeine\CategoryBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheCodeine\CategoryBundle\Form\DataTransformer\IdToCategoryTransformer;

class AddableCategoryType extends AbstractType
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $repo = $this->em->getRepository($options['class']);
        $builder
            ->add('choice', 'choice', array(//                'class' => $options['class']
            ))
            ->add('new_value', 'text', array(
                'attr' => array(
                    'placeholder' => 'Add new value'
                )
            ))
            ->addModelTransformer(new IdToCategoryTransformer($repo));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'compound' => true,
            'data_class' => 'TheCodeine\CategoryBundle\Entity\Category',
        ));
    }

    public function getName()
    {
        return 'tuna_addable_category';
    }

    public function getParent()
    {
        return 'entity';
    }
}
