<?php

namespace TheCodeine\CategoryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use TheCodeine\AdminBundle\Form\DataTransformer\ValueToChoiceOrTextTransformer;

class CategoryType extends AbstractType
{
    public function __construct($dataClass = 'TheCodeine\CategoryBundle\Entity\Category')
    {
        $this->dataClass = $dataClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => $this->dataClass,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass,
            'error_bubbling' => false
        ));
    }

    public function getName()
    {
        return 'tuna_category';
    }
}
