<?php

namespace TheCodeine\AdminBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

class TranslationsSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate',
        );
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->handleEvent($args);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->handleEvent($args);
    }

    public function handleEvent(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $entity = $args->getEntity();

        if ($entity instanceof AbstractPersonalTranslation) {
            if (empty(trim($entity->getContent()))) {
                $em->remove($entity);
            }
        }

    }

}
