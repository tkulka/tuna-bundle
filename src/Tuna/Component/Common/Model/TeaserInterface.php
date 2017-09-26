<?php

namespace TunaCMS\Component\Common\Model;

interface TeaserInterface
{
    /**
     * @param string $teaser
     *
     * @return self
     */
    public function setTeaser($teaser);

    /**
     * @return string
     */
    public function getTeaser();
}
