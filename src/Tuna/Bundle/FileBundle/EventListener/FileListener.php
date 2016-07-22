<?php

namespace TheCodeine\FileBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use TheCodeine\FileBundle\Entity\AbstractFile;
use TheCodeine\FileBundle\Manager\FileManager;

class FileListener
{
    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * FileUploadSubscriber constructor.
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * save current path to 'old' property to help file manipulations
     */
    public function postLoad(AbstractFile $file, LifecycleEventArgs $args)
    {
        $file->saveOldPath();
    }

    public function prePersist(AbstractFile $file, LifecycleEventArgs $args)
    {
        if ($file->getPath() == null) {
            $args->getEntityManager()->remove($file);
        }
    }

    public function postPersist(AbstractFile $file, LifecycleEventArgs $args)
    {
        $this->handleUpload($file, $args->getEntityManager());
    }

    public function postUpdate(AbstractFile $file, LifecycleEventArgs $args)
    {
        $this->handleUpload($file, $args->getEntityManager());
    }

    public function preRemove(AbstractFile $file, LifecycleEventArgs $args)
    {
        $file->saveOldPath();
    }

    public function postRemove(AbstractFile $file, LifecycleEventArgs $args)
    {
        $this->fileManager->removeFile($file->getOldPath());
    }

    private function handleUpload(AbstractFile $file, EntityManager $em)
    {
        if ($file->getPath() !== $file->getOldPath()) {
            if ($file->getPath() == null) {
                $em->remove($file);
            } else {
                $this->fileManager->moveTmpFile($file);
                $this->fileManager->removeFile($file->getOldPath());
            }
        }
    }
}
