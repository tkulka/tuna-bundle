<?php

namespace TheCodeine\AdminBundle\DefaultDataFixtures\ORM;

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
    protected $om;

    /**
     * @var array
     */
    protected $pages = [
        'About',
        'Contact'
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
     * @return Page
     */
    protected function getNewPage()
    {
       return new Page();
    }

    /**
     * @return Menu
     */
    protected function getNewMenu()
    {
        return new Menu();
    }

    /**
     * @param string $title
     * @param Menu|null $menuRoot
     *
     * @return Page
     */
    protected function createPage($title, Menu $menuRoot = null)
    {
        $page = $this->getNewPage();

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
    protected function createMenuItem($label, Page $page = null, Menu $menuRoot = null, $slug = null, $externalUrl = null)
    {
        $menu = $this->getNewMenu();

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
