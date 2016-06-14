<?php

namespace TheCodeine\VideoBundle\Model;

interface VideoManagerInterface
{
    /**
     * Create empty video
     *
     * @return TheCodeine\VideoBundle\Entity\Video
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