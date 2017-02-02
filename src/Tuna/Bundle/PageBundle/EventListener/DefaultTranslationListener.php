<?php

namespace TheCodeine\PageBundle\EventListener;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use TheCodeine\PageBundle\Entity\PageTranslation;

class DefaultTranslationListener
{
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'onFlush',
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof PageTranslation) {
            $page = $entity->getObject();

            if ('pl' == $entity->getLocale() && 'title' == $entity->getField()) {
                $page->setTitle($entity->getContent());
            }
        }
    }

    /**
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        $entities = array_merge(
            $uow->getScheduledEntityInsertions(),
            $uow->getScheduledEntityUpdates()
        );

        foreach ($entities as $entity) {
            if (!($entity instanceof PageTranslation)) {
                continue;
            }
            Debug::dump($entity, 2, false);
            $page = $entity->getObject();

            if ('pl' == $entity->getLocale() && 'title' == $entity->getField()) {
                $page->setTitle($entity->getContent());
            }

            $em->persist($page);
            $md = $em->getClassMetadata(get_class($page));
            $uow->recomputeSingleEntityChangeSet($md, $page);
            Debug::dump($page, 3, false);

        }
    }
}