<?php

namespace TunaCMS\Bundle\MenuBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

trait ExternalUrlTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $url;

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return $this
     *
     * @param string $url
     */
    public function setUrl($url = null)
    {
        $this->url = $url;

        return $this;
    }
}
