<?php

namespace TheCodeine\MenuBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use TheCodeine\MenuBundle\Entity\Menu;
use TheCodeine\MenuBundle\Entity\MenuTranslation;
use TheCodeine\PageBundle\Entity\Page;
use TheCodeine\PageBundle\Entity\PageTranslation;

class PageSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            'postUpdate'
        ];
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $page = $args->getEntity();

        if (!$page instanceof Page) {
            return;
        }

        $em = $args->getEntityManager();
        $menus = $em->getRepository(Menu::class)->findBy([
            'page' => $page,
        ]);

        if (!count($menus)) {
            return;
        }

        foreach ($menus as $menu) {
            MenuListener::synchronizeWithPage($menu, $page);
        }

        $em->flush();
    }
}
