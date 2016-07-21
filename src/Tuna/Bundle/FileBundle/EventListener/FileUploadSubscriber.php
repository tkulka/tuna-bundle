<?php

namespace TheCodeine\FileBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use TheCodeine\FileBundle\Entity\AbstractFile;

class FileUploadSubscriber implements EventSubscriber
{
    /**
     * FileUploadSubscriber constructor.
     */
    public function __construct()
    {
    }

    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate',
            'postRemove'
        );
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->handleUpload($args);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->handleUpload($args);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof AbstractFile) {
            return;
        }

        /* @var $entity AbstractFile */
        $this->fs->remove($entity->getOldPath());
    }

    private function handleUpload(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof AbstractFile) {
            return;
        }

        $em = $args->getEntityManager();

        /* @var $entity AbstractFile */
        if ($entity->getPath() !== $entity->getOldPath()) {
            if ($entity->getPath() == null) {
                $em->remove($entity);
            } else {
                if ($entity->getOldPath() !== null) {
                    // remove old file
                }

                // move new file from tmp to uploads
            }
        }
    }
}
