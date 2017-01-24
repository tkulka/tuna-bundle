<?php

namespace TheCodeine\ImageBundle\Form;

use TheCodeine\ImageBundle\Form\DataTransformer\IdToImageTransformer;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class ImageIdType extends AbstractType
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * @param RegistryInterface $doctrine
     */
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new IdToImageTransformer($this->doctrine));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TheCodeine\ImageBundle\Entity\Image',
            'csrf_protection' => false,
        ));
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'image';
    }
}