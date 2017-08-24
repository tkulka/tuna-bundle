<?php

namespace TunaCMS\Bundle\NodeBundle\Model;

use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;

interface NodeInterface extends RouteInterface, MenuInterface
{
    public function getId();
}
