<?php

namespace TunaCMS\Bundle\NodeBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait RouteTranslationTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $published;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return $this
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @return $this
     *
     * @param bool $published
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }
}
