<?php

namespace TunaCMS\Bundle\NodeBundle\Factory;

use TunaCMS\Bundle\MenuBundle\Factory\MenuFactory;
use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;

class NodeFactory extends MenuFactory
{
    /**
     * @param $type
     *
     * @return NodeInterface
     */
    public function getInstance($type)
    {
        return parent::getInstance($type);
    }
}
