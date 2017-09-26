<?php

namespace TunaCMS\Bundle\NodeBundle\Factory;

use TunaCMS\Bundle\MenuBundle\Factory\MenuFactory;
use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;

class NodeFactory extends MenuFactory
{
    const BASE_TYPE = 'node';

    /**
     * @param $type
     *
     * @return NodeInterface
     */
    public function getInstance($type = null)
    {
        return parent::getInstance($type);
    }
}
