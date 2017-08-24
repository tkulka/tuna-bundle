<?php

namespace TunaCMS\Bundle\NodeBundle\Repository;

use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;

interface NodeRepositoryInterface
{
    /**
     * @return NodeInterface[]
     */
    public function getMenuRoots();

    /**
     * @param NodeInterface|null $node
     *
     * @return NodeInterface
     */
    public function loadNodeTree(NodeInterface $node = null);
}
