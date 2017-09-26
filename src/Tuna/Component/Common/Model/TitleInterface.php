<?php

namespace TunaCMS\Component\Common\Model;

interface TitleInterface
{
    /**
     * @param string $title
     *
     * @return self
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getTitle();
}
