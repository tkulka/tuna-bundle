<?php

namespace TunaCMS\Bundle\NodeBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TunaCMS\Bundle\NodeBundle\Model\MetadataInterface;

class MetadataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('translations', TranslationsType::class, [
            'label' => false,
            'fields' => [
                'title' => [],
                'description' => [],
                'keywords' => [],
                'indexable' => [
                    'label' => 'Indexable',
                ],
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MetadataInterface::class,
            'translation_domain' => 'tuna_admin',
        ]);
    }
}
