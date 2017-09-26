<?php

namespace TunaCMS\Bundle\MenuBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('label')
            ->add('displayingChildren', CheckboxType::class, [
                'label' => 'display children',
            ])
            ->add('published', CheckboxType::class, [
                'label' => 'published',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MenuInterface::class,
            'translation_domain' => 'tuna_admin',
            'label' => false,
        ]);
    }
}
