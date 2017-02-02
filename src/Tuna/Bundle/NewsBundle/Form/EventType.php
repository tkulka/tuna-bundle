<?php

namespace TheCodeine\NewsBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;

use TheCodeine\NewsBundle\Entity\Category;

class EventType extends NewsType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('startDate', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss'
            ])
            ->add('endDate', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'required' => false
            ]);
    }

    protected function getTranslatableClass()
    {
        return 'TheCodeine\NewsBundle\Entity\Event';
    }
}
