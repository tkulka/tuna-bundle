<?php

namespace TheCodeine\PageBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use TheCodeine\PageBundle\Entity\AbstractPage;

class PageAliasListener
{
    public function postPersist(AbstractPage $abstractPage, LifecycleEventArgs $event)
    {
        if (null === $abstractPage->getAlias()) {
            $abstractPage->setAlias($abstractPage->getSlug());

            $em = $event->getEntityManager();

            $em->persist($abstractPage);
            $em->flush();
        }
    }
}
