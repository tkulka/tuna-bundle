<?php

namespace TheCodeine\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use TheCodeine\NewsBundle\Entity\Category;

class Builder
{
    /**
     * @var \Knp\Menu\FactoryInterface
     */
    private $factory;


    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Request $request
     * @return \Knp\Menu\ItemInterface
     */
    public function buildTopMenuStatic(Request $request)
    {
        $menu = $this->factory->createItem('root', array(
            'childrenAttributes' => array('class' => 'nav')
        ));

        $menu->addChild('Strony', array(
            'route' => 'thecodeine_page_list',
            'attributes' => array(
                "class" => preg_match_all('/thecodeine_page/i', $request->get('_route')) ? "active" : ""
            )
        ));

        $menu->addChild('Newsy', array(
            'route' => 'thecodeine_news_list',
            'attributes' => array(
                "class" => preg_match_all('/thecodeine_news/i', $request->get('_route')) ? "active" : ""
            )
        ));

        return $menu;
    }

    /**
     * @param Request $request
     * @return \Knp\Menu\ItemInterface
     */
    public function buildMenu(Request $request)
    {
        $menu = $this->factory->createItem('root', array(
            'childrenAttributes' => array('class' => 'nav')
        ));

        if (preg_match_all('/thecodeine_page/i', $request->get('_route'))) {
            $menu = $this->buildPageSubmenu($menu, $request);
        }

        if (preg_match_all('/thecodeine_news/i', $request->get('_route'))) {
            $menu = $this->buildNewsSubmenu($menu, $request);
        }

        return $menu;
    }

    private function buildPageSubmenu($menu, $request)
    {
        $menu->addChild('Lista stron', array(
            'route' => 'thecodeine_page_list',
            'attributes' => array(
                "class" => $request->get('_route') === 'thecodeine_page_list' ? "active" : ""
            )
        ));
        $menu->addChild('Nowa strona', array(
            'route' => 'thecodeine_page_create',
            'attributes' => array(
                "class" => $request->get('_route') === 'thecodeine_page_create' ? "active" : ""
            )
        ));

        return $menu;
    }

    private function buildNewsSubmenu($menu, $request)
    {
        $menu->addChild('Lista newsów', array(
            'route' => 'thecodeine_news_list',
            'attributes' => array(
                "class" => $request->get('_route') === 'thecodeine_news_list' ? "active" : ""
            )
        ));
        $menu->addChild('Nowy news', array(
            'route' => 'thecodeine_news_create',
            'attributes' => array(
                "class" => $request->get('_route') === 'thecodeine_news_create' ? "active" : ""
            )
        ));

        return $menu;
    }
}