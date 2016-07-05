<?php

namespace TheCodeine\ImageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheCodeine\ImageBundle\Form\Type\ImageFile;

class ImageRequestThumbnailType extends AbstractType
{
    /**
     * @var bool
     */
    private $hasImage;

    public function __construct($hasImage = false)
    {
        $this->hasImage = $hasImage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', new ImageFile(), array(
                'label' => false,
            ))
            ->add(
                'remove',
                'hidden',
                array(
                    'mapped' => false,
                    'data' => $this->hasImage ? 0 : 1,
                    'attr' => array('class' => 'remove-image'),
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'data_class' => 'TheCodeine\ImageBundle\Entity\Image',
        ));
    }

    public function getName()
    {
        return 'image';
    }
}
