<?php

namespace TheCodeine\NewsBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use TheCodeine\NewsBundle\Entity\Event;

class EventType extends NewsType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('startDate', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'label' => 'ui.form.labels.start.date'
            ])
            ->add('endDate', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'required' => false,
                'label' => 'ui.form.labels.end.date'
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getEntityClass()
    {
        return Event::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'thecodeine_newsbundle_event';
    }
}