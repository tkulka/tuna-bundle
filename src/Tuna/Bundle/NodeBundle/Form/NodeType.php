<?php

namespace TunaCMS\Bundle\NodeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;

class NodeType extends AbstractType
{
    protected $metadataModel;

    public function __construct($metadataModel)
    {
        $this->metadataModel = $metadataModel;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('metadata', MetadataType::class, [
            'data_class' => $this->metadataModel,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NodeInterface::class,
            'translation_domain' => 'tuna_admin',
        ]);
    }
}
