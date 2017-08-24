<?php

namespace TunaCMS\Bundle\FileBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use TunaCMS\Bundle\FileBundle\Entity\AbstractFile;
use TunaCMS\Bundle\FileBundle\Manager\FileManager;

class FileListener
{
    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * FileListener constructor.
     *
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * @param AbstractFile $file
     * @param LifecycleEventArgs $args
     */
    public function postLoad(AbstractFile $file, LifecycleEventArgs $args)
    {
        $file->savePersistedPath();
    }

    /**
     * @param AbstractFile $file
     * @param LifecycleEventArgs $args
     */
    public function postPersist(AbstractFile $file, LifecycleEventArgs $args)
    {
        $this->handleUpload($file, $args->getEntityManager());
    }

    public function prePersist(AbstractFile $file, LifecycleEventArgs $args)
    {
        if (!$file->getPath()) {
            return;
        }

        $info = $this->fileManager->getFileInfo($file);
        $file
            ->setMime($info['mime'])
            ->setFilesize($info['size']);
    }

    /**
     * @param AbstractFile $file
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(AbstractFile $file, LifecycleEventArgs $args)
    {
        $this->handleUpload($file, $args->getEntityManager());
    }

    /**
     * @param AbstractFile $file
     * @param LifecycleEventArgs $args
     */
    public function preRemove(AbstractFile $file, LifecycleEventArgs $args)
    {
        $file->savePersistedPath();
    }

    /**
     * @param AbstractFile $file
     * @param LifecycleEventArgs $args
     */
    public function postRemove(AbstractFile $file, LifecycleEventArgs $args)
    {
        $this->fileManager->removeFile($file->getPersistedPath());
    }

    /**
     * @param AbstractFile $file
     * @param EntityManager $em
     */
    private function handleUpload(AbstractFile $file, EntityManager $em)
    {
        if (!$file->getPath()) {
            $em->remove($file);
        } elseif ($file->isUploaded()) {
            $this->fileManager->moveTmpFile($file);
            $this->fileManager->removeFile($file->getPersistedPath());
        }
    }
}
