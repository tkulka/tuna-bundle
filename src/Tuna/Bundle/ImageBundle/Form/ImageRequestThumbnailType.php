<?php

namespace TheCodeine\ImageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheCodeine\ImageBundle\Entity\Image;
use TheCodeine\ImageBundle\Form\Type\ImageFile;

class ImageRequestThumbnailType extends AbstractType
{
    /**
     * @var bool
     */
    private $hasImage;

    /**
     * ImageRequestThumbnailType constructor.
     *
     * @param bool $hasImage
     */
    public function __construct($hasImage = false)
    {
        $this->hasImage = $hasImage;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', ImageFile::class, [
                'label' => false,
            ])
            ->add('remove', HiddenType::class, [
                'data' => $this->hasImage ? 0 : 1,
                'attr' => ['class' => 'remove-image'],
                'mapped' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
            'csrf_protection' => false,
            'translation_domain' => 'tuna_admin',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tuna_image';
    }
}
