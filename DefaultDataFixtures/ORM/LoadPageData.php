<?php

namespace TheCodeine\AdminBundle\DefaultDataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TheCodeine\MenuBundle\Entity\MenuInterface;
use TheCodeine\MenuBundle\Service\MenuManager;
use TheCodeine\PageBundle\Entity\Page;
use TheCodeine\PageBundle\Service\PageManager;

class LoadPageData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
     * @var MenuManager
     */
    protected $menuManager;

    /**
     * @var PageManager
     */
    protected $pageManager;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->menuManager = $container->get('the_codeine_menu.manager');
        $this->pageManager = $container->get('the_codeine_page.manager');
    }

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
        return $this->pageManager->getPageInstance();
    }

    /**
     * @return MenuInterface
     */
    protected function getNewMenu()
    {
        return $this->menuManager->getMenuInstance();
    }

    /**
     * @param string $title
     * @param MenuInterface|null $menuRoot
     *
     * @return Page
     */
    protected function createPage($title, MenuInterface $menuRoot = null)
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
     * @param MenuInterface|null $menuRoot
     * @param string|null $slug
     * @param string|null $externalUrl
     *
     * @return MenuInterface
     */
    protected function createMenuItem($label, Page $page = null, MenuInterface $menuRoot = null, $slug = null, $externalUrl = null)
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
