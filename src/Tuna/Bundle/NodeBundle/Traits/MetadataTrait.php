<?php

// TODO remove

namespace TunaCMS\Bundle\NodeBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait MetadataTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Translatable
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Translatable
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Translatable
     */
    protected $keywords;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     *
     * @Gedmo\Translatable
     */
    protected $indexable;

    /**
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return $this
     *
     * @param string|null $title
     */
    public function setTitle($title = null)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return $this
     *
     * @param string|null $description
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @return $this
     *
     * @param string|null $keywords
     */
    public function setKeywords($keywords = null)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * @return bool
     */
    public function isIndexable()
    {
        return $this->indexable;
    }

    /**
     * @return $this
     *
     * @param bool $indexable
     */
    public function setIndexable($indexable)
    {
        $this->indexable = $indexable;

        return $this;
    }
}
