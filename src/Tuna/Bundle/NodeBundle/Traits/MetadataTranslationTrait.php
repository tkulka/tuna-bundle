<?php

namespace TunaCMS\Bundle\NodeBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

trait MetadataTranslationTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $keywords;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
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
