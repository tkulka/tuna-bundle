<?php

namespace TunaCMS\Bundle\MenuBundle\Model;

use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;
use TunaCMS\Bundle\NodeBundle\Model\TreeInterface;
use TunaCMS\CommonComponent\Model\TranslatableInterface;

interface MenuInterface extends TreeInterface, TranslatableInterface
{
    const LINK_EXTERNAL = 'external';
    const LINK_NODE = 'node';
    const LINK_TYPES = [
        self::LINK_EXTERNAL,
        self::LINK_NODE,
    ];

    /**
     * Tells if item links to something, or exists just for grouping other items
     *
     * @return boolean
     */
    public function isClickable();

    /**
     * @param boolean $clickable
     *
     * @return $this
     */
    public function setClickable($clickable);

    /**
     * Tells if item should display its children
     *
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
     * In case your MenuInterface implementation implements also NodeInterface you can `return $this;` here and in `setNode()`
     *
     * @return NodeInterface
     */
    public function getNode();

    /**
     * @param NodeInterface|null $node
     *
     * @return $this
     */
    public function setNode(NodeInterface $node = null);

    /**
     * Tells if link is external (using `getUrl`), or connected with node (using `getNode`)
     *
     * @return string
     */
    public function getLinkType();

    /**
     * @param string $linkType
     *
     * @return $this
     */
    public function setLinkType($linkType);

    /**
     * ##########################################
     *
     *          Translatable fields:
     *
     * ##########################################
     */

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
     * @return string|null
     */
    public function getUrl();

    /**
     * @param string|null $url
     *
     * @return $this
     */
    public function setUrl($url = null);
}
