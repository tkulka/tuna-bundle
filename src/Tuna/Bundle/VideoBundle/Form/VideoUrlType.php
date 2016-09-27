<?php

namespace TheCodeine\VideoBundle\Form;

use TheCodeine\VideoBundle\Model\VideoManagerInterface;
use TheCodeine\VideoBundle\Form\DataTransformer\UrlToTypeAndIdTransformer;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class VideoUrlType extends AbstractType
{
    /**
     * Tag manager
     *
     * @var TagManagerInterface
     */
    private $videoManager;

    public function __construct(VideoManagerInterface $videoManager)
    {
        $this->videoManager = $videoManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new UrlToTypeAndIdTransformer($this->videoManager));
    }

    public function setOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
             'translation_domain' => 'tuna_admin',
        ));
    }

    public function getParent()
    {
        return 'url';
    }

    public function getName()
    {
        return 'thecodeine_videobundle_url';
    }
}
