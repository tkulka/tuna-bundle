<?php

namespace TheCodeine\PageBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use TheCodeine\PageBundle\Entity\Page;

class PageAliasListener
{
    public function postPersist(Page $page, LifecycleEventArgs $event)
    {
        if ($page->getAlias() == null) {
            $em = $event->getEntityManager();
            $page->setAlias($page->getSlug());
            $em->persist($page);
            $em->flush();
        }
    }
}
