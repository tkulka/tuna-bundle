<?php

namespace TunaCMS\Component\Common\Model;

interface SlugInterface
{
    /**
     * @param string $slug
     *
     * @return self
     */
    public function setSlug($slug);

    /**
     * @return string
     */
    public function getSlug();
}
