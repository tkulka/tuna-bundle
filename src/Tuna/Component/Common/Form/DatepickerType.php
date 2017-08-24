<?php

namespace TunaCMS\CommonComponent\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatepickerType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd HH:mm:ss',
            'translation_domain' => 'tuna_admin',
        ]);
    }

    public function getParent()
    {
        return DateTimeType::class;
    }
}
