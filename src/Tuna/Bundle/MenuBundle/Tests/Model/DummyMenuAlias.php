<?php

namespace TunaCMS\Bundle\MenuBundle\Tests\Model;

use TunaCMS\Bundle\MenuBundle\Model\AbstractMenu;
use TunaCMS\Bundle\MenuBundle\Model\MenuAliasInterface;
use TunaCMS\Bundle\MenuBundle\Traits\MenuAliasTrait;

class DummyMenuAlias extends AbstractMenu implements MenuAliasInterface
{
    use MenuAliasTrait;
}