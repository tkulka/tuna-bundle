<?php

namespace TheCodeine\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\ORM\EntityManager;
use TheCodeine\PageBundle\Entity\Page;
use TheCodeine\MenuBundle\Entity\Menu;

class LoadPageData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @var EntityManager
     */
    private $om;

    /**
     * @var array
     */
    private $pages = [
        'O nas',
        'Kontakt'
    ];

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $om)
    {
        $this->om = $om;

        $mainMenu = $this->createMenuItem('Menu');

        foreach ($this->pages as $value) {
            $this->createPage($value, $mainMenu);
        }

        $om->flush();

    }

    /**
     * @param string $title
     * @param Menu|null $menuRoot
     *
     * @return Page
     */
    private function createPage($title, Menu $menuRoot = null)
    {
        $page = new Page();

        $page
            ->setTitle($title)
            ->setPublished(true);

        $this->om->persist($page);
        $this->om->flush();

        if ($menuRoot) {
            $this->createMenuItem($title, $page, $menuRoot);
        }

        return $page;
    }

    /**
     * @param string $label
     * @param Page|null $page
     * @param Menu|null $menuRoot
     * @param string|null $slug
     * @param string|null $externalUrl
     *
     * @return Menu
     */
    private function createMenuItem($label, Page $page = null, Menu $menuRoot = null, $slug = null, $externalUrl = null)
    {
        $menu = new Menu();

        $menu
            ->setPage($page)
            ->setLabel($label)
            ->setSlug($slug)
            ->setExternalUrl($externalUrl)
            ->setParent($menuRoot);

        $this->om->persist($menu);
        $this->om->flush();

        return $menu;
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
