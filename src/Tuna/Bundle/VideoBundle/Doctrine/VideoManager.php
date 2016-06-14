<?php

namespace TheCodeine\VideoBundle\Doctrine;

use TheCodeine\VideoBundle\Entity\Video;
use TheCodeine\VideoBundle\Model\VideoManagerInterface;
use Doctrine\ORM\EntityManager;

class VideoManager implements VideoManagerInterface
{
    /**
     * Tag entity repository class
     *
     * @var Doctrine\ORM\EntityRepository
     */
    protected $repository;

    public function __construct(EntityManager $entityManager)
    {
        $this->repository = $entityManager->getRepository('TheCodeine\VideoBundle\Entity\Video');
    }

    public function createVideo()
    {
        return new Video();
    }

    public function findByVideoId($videoId)
    {
        return $this->repository->findOneBy(array('video_id' => $videoId));
    }
}