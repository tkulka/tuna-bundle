<?php

namespace TunaCMS\Bundle\MenuBundle\Model;

use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;
use TunaCMS\Bundle\NodeBundle\Model\TreeInterface;
use TunaCMS\CommonComponent\Model\TranslatableInterface;

interface MenuInterface extends TreeInterface, TranslatableInterface
{
    const LINK_URL = 'url';
    const LINK_NODE = 'node';

    /**
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
     * @return boolean
     */
    public function isUrlLinkType();

    /**
     * @return boolean
     */
    public function isExternalNodeLinkType();

    /**
     * @return boolean
     */
    public function isNodeLinkType();

    /**
     * ##########################################
     *
     *          Translatable fields:
     *
     * ##########################################
     */

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
