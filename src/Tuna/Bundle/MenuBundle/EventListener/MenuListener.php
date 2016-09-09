<?php

namespace TheCodeine\MenuBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use TheCodeine\MenuBundle\Entity\Menu;

class MenuListener
{
    public function preFlush(Menu $menu, PreFlushEventArgs $args)
    {
        $this->synchronizeMenuWithPage($menu);
    }

    private function synchronizeMenuWithPage(Menu $menu)
    {
        if ($menu->getPage() !== null) {
            $menu->setPath($menu->getPage()->getSlug());
            $menu->setExternalUrl(null);
            $menu->getPage()->setPublished($menu->isPublished());
        }
    }
}
