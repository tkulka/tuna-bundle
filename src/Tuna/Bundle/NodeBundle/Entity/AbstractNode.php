<?php

namespace TunaCMS\Bundle\NodeBundle\Entity;

use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;
use TunaCMS\Bundle\NodeBundle\Traits\NodeTrait;
use TunaCMS\Component\Common\Traits\IdTrait;

class AbstractNode implements NodeInterface
{
    use IdTrait;
    use NodeTrait;
}
