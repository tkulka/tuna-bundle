<?php

namespace TheCodeine\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use TheCodeine\AdminBundle\Form\DataTransformer\ValueToChoiceOrTextTransformer;

class ExtendableSelectType extends AbstractType
{
    const NEW_LABEL = 'Add new';
    const NEW_VALUE = 'new';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array();
        foreach ($options['choices'] as $choice) {
            $choices[$choice] = $choice;
        }
        $builder
            ->add('choice', 'choice', array(
                'choices' => $choices + array(self::NEW_VALUE => self::NEW_LABEL),
            ))
            ->add('new_value', 'text', array(
                'attr' => array(
                    'placeholder' => 'Add new value'
                )
            ))
            ->addModelTransformer(new ValueToChoiceOrTextTransformer($choices));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'error_mapping' => array(
                    '.' => 'new_value',
                ),
            ))
            ->setRequired(array('choices'))
            ->setAllowedTypes(array('choices' => 'array'));
    }

    public function getName()
    {
        return 'tuna_extendable_select';
    }

}
