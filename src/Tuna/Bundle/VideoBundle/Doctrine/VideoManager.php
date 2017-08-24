<?php

namespace TunaCMS\Bundle\VideoBundle\Doctrine;

use Doctrine\ORM\EntityRepository;
use TunaCMS\Bundle\VideoBundle\Entity\Video;
use Doctrine\ORM\EntityManager;

class VideoManager implements VideoManagerInterface
{
    /**
     * Tag entity repository class
     *
     * @var EntityRepository
     */
    protected $repository;

    /**
     * VideoManager constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->repository = $entityManager->getRepository(Video::class);
    }

    /**
     * @return Video
     */
    public function createVideo()
    {
        return new Video();
    }

    /**
     * @param string $videoId
     *
     * @return Video
     */
    public function findByVideoId($videoId)
    {
        return $this->repository->findOneBy(['video_id' => $videoId]);
    }
}
