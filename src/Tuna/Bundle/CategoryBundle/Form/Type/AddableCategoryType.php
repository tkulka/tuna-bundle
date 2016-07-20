<?php

namespace TheCodeine\CategoryBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheCodeine\CategoryBundle\Form\CategoryType;
use TheCodeine\CategoryBundle\Form\DataTransformer\IdToCategoryTransformer;

class AddableCategoryType extends AbstractType
{
    const NEW_VALUE_OPTION = 'new';
    const NEW_VALUE_FIELD = 'new_value';
    const CHOICE_FIELD = 'choice';

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
            ->add(self::CHOICE_FIELD, 'choice', array(
                'choices' => $this->getChoices($repo)
            ))
            ->add(self::NEW_VALUE_FIELD, new CategoryType($options['class']), array())
            ->addModelTransformer(new IdToCategoryTransformer($repo));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'compound' => true,
            'class' => '',
            'error_bubbling' => false,
            'error_mapping' => array(
                '.' => self::NEW_VALUE_FIELD
            )
        ));
    }

    public function getName()
    {
        return 'tuna_addable_category';
    }

    private function getChoices(EntityRepository $repo)
    {
        $choices = array();
        $entities = $repo->findAll();

        foreach ($entities as $entity) {
            $choices[$entity->getId()] = $entity->getName();
        }
        $choices[self::NEW_VALUE_OPTION] = 'category.new';

        return $choices;
    }
}
