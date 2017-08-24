<?php

namespace TunaCMS\Bundle\NodeBundle\Model;

use TunaCMS\CommonComponent\Model\TranslatableInterface;

interface MetadataInterface extends TranslatableInterface
{
    /**
     * @return string|null
     */
    public function getTitle();

    /**
     * @param string|null $title
     *
     * @return $this
     */
    public function setTitle($title = null);

    /**
     * @return string|null
     */
    public function getDescription();

    /**
     * @param string|null $description
     *
     * @return $this
     */
    public function setDescription($description = null);

    /**
     * @return string|null
     */
    public function getKeywords();

    /**
     * @param string|null $keywords
     *
     * @return $this
     */
    public function setKeywords($keywords = null);

    /**
     * @return boolean
     */
    public function isIndexable();

    /**
     * @param boolean $indexable
     *
     * @return $this
     */
    public function setIndexable($indexable);
}
