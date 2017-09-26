<?php

namespace TunaCMS\Bundle\MenuBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TunaCMS\Bundle\MenuBundle\Model\ExternalUrlInterface;

class ExternalUrlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('url');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExternalUrlInterface::class,
        ]);
    }

    public function getParent()
    {
        return MenuType::class;
    }
}
