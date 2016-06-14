<?php

namespace TheCodeine\ImageBundle\Form\EventListener;

use TheCodeine\ImageBundle\Form\ImageIdType;

use TheCodeine\ImageBundle\Form\ImageRemoteType;

use TheCodeine\ImageBundle\Form\ImageRequestType;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Bridge\Doctrine\RegistryInterface;

use TheCodeine\ImageBundle\Form\DataTransformer\IdToImageTransformer;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ImageListener implements EventSubscriberInterface
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * @var FormFactoryInterface
     */
    private $factory;

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SUBMIT => 'preSubmit',
        );
    }

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(RegistryInterface $doctrine, FormFactoryInterface $factory)
    {
        $this->doctrine = $doctrine;
        $this->factory = $factory;
    }

    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if ($this->isRequestImage($data)) {
            $form->add('image', new ImageRequestType());
        } elseif ($this->isRemoteImage($data)) {
            $form->add('image', new ImageRemoteType());
        } else {
            $form->add('image', new ImageIdType($this->doctrine));
        }
    }

    private function isRequestImage(array $data)
    {
        return isset($data['image']['file']) && $data['image']['file'] instanceof UploadedFile;
    }

    private function isRemoteImage(array $data)
    {
        return isset($data['image']['file']) && is_string($data['image']['file']) &&
        (!strncasecmp('http://', $data['image']['file'], 7) || !strncasecmp('https://', $data['image']['file'], 8));
    }
}
