<?php

namespace TheCodeine\ImageBundle\EventListener;

use Symfony\Component\HttpFoundation\File\File;

use TheCodeine\ImageBundle\Entity\Image;
use TheCodeine\ImageBundle\Model\ImageManagerInterface;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class UploadListener implements EventSubscriber
{
    private $imageManager;

    public function __construct(ImageManagerInterface $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::preUpdate,
        );
    }

    public function preUpdate(LifecycleEventArgs $args)
    {

        if (!$args->getEntity() instanceof Image) {
            return;
        }

        $entity = $args->getEntity();
        $em     = $args->getEntityManager();

        $this->storeImage($args->getEntity());


        $uow    = $em->getUnitOfWork();
        $meta   = $em->getClassMetadata(get_class($entity));
        $uow->recomputeSingleEntityChangeSet($meta, $entity);

    }

    public function prePersist(LifecycleEventArgs $args)
    {
        if (!$args->getEntity() instanceof Image) {
            return;
        }

        return $this->storeImage($args->getEntity());

    }

    private function storeImage(Image $image)
    {
        $file = $this->getFile($image);

        if (!$file instanceof \SplFileInfo) {
            return;
        }
        $path = $this->imageManager->store($file);

        return $image->setPath($path);
    }

    private function getFile(Image $image)
    {
        if ($image->getFile() instanceof \SplFileInfo) {
            return $image->getFile();
        }

        if (null !== $image->getUrl()) {
            return new File($this->downloadFile($image->getUrl()));
        }

        return null;
    }

    private function downloadFile($url)
    {
        $tmpfile = tempnam(sys_get_temp_dir(), 'utft');
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        if (!empty($extension)) {
            $tmpfile = sprintf("%s.%s", $tmpfile, $extension);
        }

        $fp = fopen($tmpfile, 'w+');
        if (false === $fp) {
            throw new \RuntimeException(sprintf('Cannot open temporary file `%s`', $tmpfile));
        }

        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_BINARYTRANSFER => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FILE => $fp,
            CURLOPT_TIMEOUT => 50,
        ));

        if (false === curl_exec($ch)) {
            throw new \RuntimeException(sprintf('Cannot download remote file. Curl error: `%s`', curl_error($ch)));
        }
        curl_close($ch);

        return $tmpfile;
    }
}