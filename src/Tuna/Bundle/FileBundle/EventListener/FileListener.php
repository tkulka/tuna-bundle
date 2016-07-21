<?php

namespace TheCodeine\FileBundle\EventListener;

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

    public function postLoad(AbstractFile $file, LifecycleEventArgs $args)
    {
        $args->getEntity()->saveOldPath();
    }

    public function postPersist(AbstractFile $file, LifecycleEventArgs $args)
    {
        $this->handleUpload($args);
    }

    public function postUpdate(AbstractFile $file, LifecycleEventArgs $args)
    {
        $this->handleUpload($args);
    }

    public function postRemove(AbstractFile $file, LifecycleEventArgs $args)
    {
        $this->fileManager->removeFile($file);
    }

    private function handleUpload(LifecycleEventArgs $args)
    {
        $file = $args->getEntity();
        $em = $args->getEntityManager();

        /* @var $file AbstractFile */
        if ($file->getPath() !== $file->getOldPath()) {
            if ($file->getPath() == null) {
                $em->remove($file);
            } else {
                $this->fileManager->removeFile($file);
                $this->fileManager->moveTmpFile($file);
            }
        }
    }
}
