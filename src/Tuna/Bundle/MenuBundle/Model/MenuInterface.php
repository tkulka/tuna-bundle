<?php

namespace TunaCMS\Bundle\MenuBundle\Model;

use TunaCMS\Bundle\NodeBundle\Model\TreeInterface;

interface MenuInterface extends TreeInterface
{
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
}
