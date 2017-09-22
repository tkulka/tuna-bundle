<?php

namespace TunaCMS\Bundle\NodeBundle\Model;

use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;

interface NodeInterface extends RouteInterface, MenuInterface
{
    /**
     * @return integer
     */
    public function getId();
}
