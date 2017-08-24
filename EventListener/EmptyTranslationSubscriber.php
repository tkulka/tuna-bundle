<?php

namespace TunaCMS\AdminBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

class EmptyTranslationSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            'postUpdate',
            'postPersist',
        ];
    }

    public function postUpdate(LifecycleEventArgs $event)
    {
        $this->removeEmptyTranslation($event);
    }

    public function postPersist(LifecycleEventArgs $event)
    {
        $this->removeEmptyTranslation($event);
    }

    protected function removeEmptyTranslation(LifecycleEventArgs $event)
    {
        $translation = $event->getEntity();

        if (!$translation instanceof AbstractPersonalTranslation) {
            return;
        }

        if ($translation->getContent() === null) {
            $event->getEntityManager()->remove($translation);
        }
    }
}
