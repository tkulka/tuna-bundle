<?php

namespace TheCodeine\CategoryBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheCodeine\CategoryBundle\Entity\Category;
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

    /**
     * AddableCategoryType constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $repo = $this->em->getRepository($options['class']);

        $choiceOptions = [
            'choices' => $this->getChoices($repo),
            'attr' => array(
                'class' => 'filtered',
            )
        ];
        if ($options['nullable']) {
            $choiceOptions['empty_value'] = 'components.categories.form.empty';
        }
        $builder
            ->add(self::CHOICE_FIELD, ChoiceType::class, $choiceOptions)
            ->add(self::NEW_VALUE_FIELD, new CategoryType($options['class']), [])
            ->addModelTransformer(new IdToCategoryTransformer($repo));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Category::class,
            'compound' => true,
            'error_mapping' => ['.' => self::NEW_VALUE_FIELD],
            'error_bubbling' => false,
            'translation_domain' => 'tuna_admin',
            'nullable' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tuna_addable_category';
    }

    /**
     * @param EntityRepository $repo
     *
     * @return array
     */
    private function getChoices(EntityRepository $repo)
    {
        $choices = [];
        $entities = $repo->findAll();

        foreach ($entities as $entity) {
            $choices[$entity->getId()] = $entity->getName();
        }
        return [
            self::NEW_VALUE_OPTION => 'components.categories.form.new',
            'components.categories.form.existing' => $choices,
        ];
    }
}
