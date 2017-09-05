<?php

namespace TunaCMS\Bundle\MenuBundle\Traits;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

trait MenuTranslationTrait
{
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\NotBlank
     */
    protected $label;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $url;

    /**
     * @return null|string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return $this
     *
     * @param null|string $label
     */
    public function setLabel($label = null)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return $this
     *
     * @param null|string $url
     */
    public function setUrl($url = null)
    {
        $this->url = $url;

        return $this;
    }
}
