<?php

namespace TheCodeine\MenuBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use TunaCMS\PageComponent\Model\PageInterface;

class PageSubscriber implements EventSubscriber
{
    /**
     * @var string FQCN of Menu
     */
    protected $menuModel;

    public function __construct($menuModel)
    {
        $this->menuModel = $menuModel;
    }

    public function getSubscribedEvents()
    {
        return [
            'postUpdate',
        ];
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $page = $args->getEntity();

        if (!$page instanceof PageInterface) {
            return;
        }

        $em = $args->getEntityManager();
        $menus = $em->getRepository($this->menuModel)->findBy([
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
