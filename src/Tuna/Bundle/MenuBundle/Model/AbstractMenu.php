<?php

namespace TunaCMS\Bundle\MenuBundle\Model;

use TunaCMS\Bundle\MenuBundle\Traits\MenuTrait;

abstract class AbstractMenu implements MenuInterface
{
    use MenuTrait;

    public function __construct()
    {
        $this->menuConstructor();
    }
}
