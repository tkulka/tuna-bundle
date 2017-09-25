<?php

namespace TunaCMS\Bundle\MenuBundle\Model;

use TunaCMS\Bundle\NodeBundle\Model\TreeInterface;
use TunaCMS\CommonComponent\Model\IdInterface;

interface MenuInterface extends TreeInterface, IdInterface
{
    /**
     * @return boolean
     */
    public function isPublished();

    /**
     * @param boolean|null $published
     *
     * @return $this
     */
    public function setPublished($published = null);

    /**
     * @return boolean
     */
    public function isDisplayingChildren();

    /**
     * @param boolean $displayingChildren
     *
     * @return $this
     */
    public function setDisplayingChildren($displayingChildren);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return $this
     *
     * @param string $name
     */
    public function setName($name = null);

    /**
     * @return string
     */
    public function getSlug();

    /**
     * @return $this
     *
     * @param string $slug
     */
    public function setSlug($slug = null);

    /**
     * @return string|null
     */
    public function getLabel();

    /**
     * @param string|null $label
     *
     * @return $this
     */
    public function setLabel($label = null);

    /**
     * @return boolean
     */
    public function isClickable();

    /**
     * @return boolean|null
     */
    public function isEmptySlug();

    /**
     * @param boolean|null $emptySlug
     *
     * @return $this
     */
    public function setEmptySlug($emptySlug = null);
}
