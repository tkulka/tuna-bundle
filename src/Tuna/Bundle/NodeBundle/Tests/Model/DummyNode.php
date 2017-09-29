<?php

namespace TunaCMS\Bundle\NodeBundle\Tests\Model;

use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;
use TunaCMS\Bundle\NodeBundle\Traits\NodeTrait;
use TunaCMS\Component\Common\Traits\IdTrait;

class DummyNode implements NodeInterface
{
    use IdTrait;
    use NodeTrait;
}