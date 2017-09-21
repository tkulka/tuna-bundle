<?php

namespace TunaCMS\Bundle\MenuBundle\Model;

interface ExternalUrlInterface extends MenuInterface
{
    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return $this
     *
     * @param string|null $url
     */
    public function setUrl($url = null);
}
