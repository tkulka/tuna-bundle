<?php

namespace TunaCMS\Bundle\NodeBundle\Model;

use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;

interface MenuNodeInterface extends MenuInterface
{
    /**
     * @return NodeInterface
     */
    public function getNode();

    /**
     * @param NodeInterface $node
     *
     * @return $this
     */
    public function setNode(NodeInterface $node = null);
}
