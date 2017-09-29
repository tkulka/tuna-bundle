<?php

namespace TunaCMS\Bundle\NodeBundle\Tests\Model;

use TunaCMS\Bundle\MenuBundle\Traits\MenuTrait;
use TunaCMS\Bundle\NodeBundle\Model\MenuNodeInterface;
use TunaCMS\Bundle\NodeBundle\Traits\MenuNodeTrait;

class DummyMenuNode implements MenuNodeInterface
{
    use MenuTrait;
    use MenuNodeTrait;
}