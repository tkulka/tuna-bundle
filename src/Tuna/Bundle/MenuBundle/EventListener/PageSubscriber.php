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
        return array(
            'postUpdate'
        );
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $page = $args->getEntity();

        if (!$page instanceof Page) {
            return;
        }

        $em = $args->getEntityManager();
        $menus = $em->getRepository('TheCodeineMenuBundle:Menu')->findBy(array(
            'page' => $page,
        ));

        if (!count($menus)) {
            return;
        }

        foreach ($menus as $menu) {
            $menu
                ->setLabel($page->getTitle())
                ->setPath($page->getSlug())
                ->setPublished($page->isPublished());

            $this::overrideTranslations($page, $menu);
        }

        $em->flush();
    }

    public static function overrideTranslations(Page $page, Menu $menu)
    {
        $titleTranslations = array();
        foreach ($page->getTranslations() as $t) {
            if ($t->getField() == 'title') {
                $titleTranslations[$t->getLocale()] = $t->getContent();
            }
        }
        dump($titleTranslations);

        foreach ($menu->getTranslations() as $t) {
            if ($t->getField() == 'label' && key_exists($t->getLocale(), $titleTranslations)) {
                $t->setContent($titleTranslations[$t->getLocale()]);
                unset($titleTranslations[$t->getLocale()]);
            }
        }

        foreach ($titleTranslations as $locale => $title) {
            $menu->addTranslation(new MenuTranslation(
                'label',
                $locale,
                $title
            ));
        }
    }
}
