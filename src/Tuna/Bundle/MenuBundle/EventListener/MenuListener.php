<?php

namespace TheCodeine\MenuBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use TheCodeine\MenuBundle\Entity\Menu;
use TheCodeine\MenuBundle\Entity\MenuTranslation;
use TheCodeine\PageBundle\Entity\Page;

class MenuListener
{
    public function prePersist(Menu $menu, LifecycleEventArgs $args)
    {
        self::synchronizeWithPage($menu, $menu->getPage());
    }

    public function preFlush(Menu $menu, PreFlushEventArgs $args)
    {
        if ($menu->getPage()) {
            $menu->getPage()->setPublished($menu->isPublished());
            $menu->setPath($menu->getPage()->getSlug());
        }
    }

    public static function synchronizeWithPage(Menu $menu = null, Page $page = null)
    {
        if ($menu == null || $page == null && ($page = $menu->getPage()) == null) {
            return;
        }

        $menu
            ->setLabel($page->getTitle())
            ->setPath($page->getSlug())
            ->setPublished($page->isPublished())
            ->setExternalUrl(null);

        $titleTranslations = [];
        foreach ($page->getTranslations() as $t) {
            if ($t->getField() == 'title') {
                $titleTranslations[$t->getLocale()] = $t->getContent();
            }
        }

        foreach ($menu->getTranslations() as $t) {
            if ($t->getField() == 'label' && key_exists($t->getLocale(), $titleTranslations)) {
                $t->setContent($titleTranslations[$t->getLocale()]);
                unset($titleTranslations[$t->getLocale()]);
            }
        }

        foreach ($titleTranslations as $locale => $title) {
            $menu->addTranslation(new MenuTranslation('label', $locale, $title));
        }
    }
}
