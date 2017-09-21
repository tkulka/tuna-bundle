<?php

namespace TunaCMS\Bundle\NodeBundle\Model;

interface NodeInterface extends RouteInterface
{
    /**
     * @return integer
     */
    public function getId();
}
