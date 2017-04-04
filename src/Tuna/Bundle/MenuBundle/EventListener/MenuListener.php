<?php

namespace TheCodeine\MenuBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Gedmo\Sluggable\Util\Urlizer;
use TheCodeine\MenuBundle\Entity\Menu;
use TheCodeine\MenuBundle\Entity\MenuTranslation;
use TunaCMS\PageComponent\Model\PageInterface;

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
            $menu->setPath(self::getPathFromPage($menu->getPage()));
        }
    }

    public static function synchronizeWithPage(Menu $menu = null, PageInterface $pageInterface = null)
    {
        if ($menu == null || $pageInterface == null && ($pageInterface = $menu->getPage()) == null) {
            return;
        }

        $menu
            ->setLabel($pageInterface->getTitle())
            ->setPath(self::getPathFromPage($pageInterface))
            ->setPublished($pageInterface->isPublished())
            ->setExternalUrl(null);

        if (!$pageInterface->getTranslations()) {
            // there's no translations, don't do anything
            return;
        }

        $titleTranslations = [];

        foreach ($pageInterface->getTranslations() as $t) {
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

    private static function getPathFromPage(PageInterface $page)
    {
        return Urlizer::transliterate($page->getTitle());
    }
}
