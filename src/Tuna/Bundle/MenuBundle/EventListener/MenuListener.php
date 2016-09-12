<?php

namespace TheCodeine\MenuBundle\EventListener;

use Doctrine\ORM\Event\PreFlushEventArgs;
use TheCodeine\MenuBundle\Entity\Menu;

class MenuListener
{
    public function preFlush(Menu $menu, PreFlushEventArgs $args)
    {
        $menu->overridePagePublishedState();
        $menu->synchronizeWithPage();
    }
}
