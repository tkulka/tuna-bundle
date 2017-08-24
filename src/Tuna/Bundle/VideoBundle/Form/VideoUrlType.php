<?php

namespace TunaCMS\Bundle\VideoBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\UrlType;
use TunaCMS\Bundle\VideoBundle\Doctrine\VideoManagerInterface;
use TunaCMS\Bundle\VideoBundle\Form\DataTransformer\UrlToTypeAndIdTransformer;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class VideoUrlType extends AbstractType
{
    /**
     * Tag manager
     *
     * @var VideoManagerInterface
     */
    private $videoManager;

    /**
     * VideoUrlType constructor.
     *
     * @param VideoManagerInterface $videoManager
     */
    public function __construct(VideoManagerInterface $videoManager)
    {
        $this->videoManager = $videoManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new UrlToTypeAndIdTransformer($this->videoManager));
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
             'translation_domain' => 'tuna_admin',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return UrlType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tuna_video';
    }
}
