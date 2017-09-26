<?php

namespace TunaCMS\Component\Common\Model;

interface PublishInterface
{
    /**
     * @param boolean $published
     *
     * @return self
     */
    public function setPublished($published);

    /**
     * @return boolean
     */
    public function getPublished();

    /**
     * @return boolean
     */
    public function isPublished();
}
