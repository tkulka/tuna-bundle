<?php

namespace TheCodeine\TagBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagsJsonArrayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function ($tagsAsArray) {
                if (!$tagsAsArray) return '';

                return implode(',', $tagsAsArray);
            },
            function ($tagsAsString) {
                return explode(',', $tagsAsString);
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'data-role' => 'tagsinput',
                'placeholder' => false,
                'class' => 'tagsinput tagsinput-primary'
            ]
        ]);
    }

    public function getParent()
    {
        return TextType::class;
    }
}
