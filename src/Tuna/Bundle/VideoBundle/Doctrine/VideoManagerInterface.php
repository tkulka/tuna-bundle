<?php

namespace TunaCMS\Bundle\VideoBundle\Doctrine;

use TunaCMS\Bundle\VideoBundle\Entity\Video;

interface VideoManagerInterface
{
    /**
     * Create empty video
     *
     * @return Video
     */
    public function createVideo();

    /**
     * Find video by its videoId.
     *
     * @param string $videoId
     *
     * @return \Traversable
     */
    public function findByVideoId($videoId);
}
