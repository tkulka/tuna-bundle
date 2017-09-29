<?php

namespace TunaCMS\Bundle\MenuBundle\Tests\Model;

use TunaCMS\Bundle\MenuBundle\Model\AbstractMenu;
use TunaCMS\Bundle\MenuBundle\Model\ExternalUrlInterface;
use TunaCMS\Bundle\MenuBundle\Traits\ExternalUrlTrait;

class DummyExternalUrl extends AbstractMenu implements ExternalUrlInterface
{
    use ExternalUrlTrait;
}