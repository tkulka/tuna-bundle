<?php

namespace TunaCMS\Bundle\NodeBundle\Model;

use TunaCMS\CommonComponent\Traits\IdTrait;

/**
 * AbstractMetadata
 */
abstract class Metadata implements MetadataInterface
{
    use IdTrait;

    public function __construct()
    {
        $this->setIndexable(true);
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->translate()->getTitle();
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title = null)
    {
        $this->translate()->setTitle($title);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->translate()->getDescription();
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description = null)
    {
        $this->translate()->setDescription($description);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getKeywords()
    {
        return $this->translate()->getKeywords();
    }

    /**
     * {@inheritdoc}
     */
    public function setKeywords($keywords = null)
    {
        $this->translate()->setKeywords($keywords);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isIndexable()
    {
        return $this->translate()->isIndexable();
    }

    /**
     * {@inheritdoc}
     */
    public function setIndexable($indexable)
    {
        $this->translate()->setIndexable($indexable);

        return $this;
    }
}
