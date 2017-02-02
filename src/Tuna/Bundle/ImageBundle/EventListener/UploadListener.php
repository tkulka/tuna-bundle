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
    /**
     * @var ImageManagerInterface
     */
    private $imageManagerInterface;

    /**
     * UploadListener constructor.
     *
     * @param ImageManagerInterface $imageManagerInterface
     */
    public function __construct(ImageManagerInterface $imageManagerInterface)
    {
        $this->imageManagerInterface = $imageManagerInterface;
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Image) {
            return;
        }

        $this->storeImage($entity);

        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $meta = $em->getClassMetadata(get_class($entity));

        $uow->recomputeSingleEntityChangeSet($meta, $entity);
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @return null|Image
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Image) {
            return null;
        }

        return $this->storeImage($entity);
    }

    /**
     * @param Image $image
     *
     * @return null|Image
     */
    private function storeImage(Image $image)
    {
        $file = $this->getFile($image);

        if (!$file instanceof \SplFileInfo) {
            return null;
        }

        $path = $this->imageManagerInterface->store($file);

        return $image->setPath($path);
    }

    /**
     * @param Image $image
     *
     * @return null|File
     */
    private function getFile(Image $image)
    {
        $file = $image->getFile();

        if ($file instanceof \SplFileInfo) {
            return $file;
        }

        $url = $image->getUrl();

        if (null !== $url) {
            return new File($this->downloadFile($url));
        }

        return null;
    }

    /**
     * @param $url
     *
     * @return string
     */
    private function downloadFile($url)
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'utft');
        $extension = pathinfo($url, PATHINFO_EXTENSION);

        if (!empty($extension)) {
            $tmpFile = sprintf("%s.%s", $tmpFile, $extension);
        }

        $fp = fopen($tmpFile, 'w+');
        if (false === $fp) {
            throw new \RuntimeException(sprintf('Cannot open temporary file `%s`', $tmpFile));
        }

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_BINARYTRANSFER => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FILE => $fp,
            CURLOPT_TIMEOUT => 50,
        ]);

        if (false === curl_exec($ch)) {
            throw new \RuntimeException(sprintf('Cannot download remote file. Curl error: `%s`', curl_error($ch)));
        }
        curl_close($ch);

        return $tmpFile;
    }
}