<?php

namespace TheCodeine\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use TheCodeine\NewsBundle\Entity\News;

class Builder
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    private $componentsConfig;

    /**
     * @param FactoryInterface $factory
     * @param String $enableTranslations
     */
    public function __construct(FactoryInterface $factory, $componentsConfig)
    {
        $this->factory = $factory;
        $this->componentsConfig = $componentsConfig;
    }

    /**
     * @param Request $request
     * @return ItemInterface
     */
    public function getTopMenu(Request $request)
    {
        $menu = $this->buildTopMenu($request);
        $this->reorderMenu($menu);

        return $menu;
    }

    /**
     * @param ItemInterface
     * @return ItemInterface
     */
    protected function reorderMenu(ItemInterface $menu)
    {
        $order = array();
        foreach ($menu->getChildren() as $label => $item) {
            $order[$label] = $item->getExtra('position');
        }
        asort($order);
        $menu->reorderChildren(array_keys($order));

        return $menu;
    }

    /**
     * @param Request $request
     * @return ItemInterface
     */
    protected function buildTopMenu(Request $request)
    {
        $menu = $this->factory->createItem('root', array(
            'childrenAttributes' => array('class' => 'nav')
        ));

        if ($this->componentsConfig['menu']['enabled']) {
            $this->addChild($menu, $request, 'Menu', 'tuna_menu_list', 100, function ($request, $route) {
                return preg_match_all('/tuna_menu_/i', $request->get('_route'));
            });
        }

        if ($this->componentsConfig['pages']['enabled']) {
            $this->addChild($menu, $request, 'Pages', 'tuna_page_list', 100, function ($request, $route) {
                return preg_match_all('/tuna_page/i', $request->get('_route'));
            });
        }

        if ($this->componentsConfig['news']['enabled']) {
            $this->addChild($menu, $request, 'News', 'tuna_news_list', 110, function ($request, $route) {
                return
                    preg_match_all('/tuna_news_/i', $request->get('_route')) &&
                    (
                        $request->attributes->get('newsType') == 'News' ||
                        $request->attributes->get('news') instanceof News
                    );
            }, array(
                'newsType' => 'News'
            ));
        }

        if ($this->componentsConfig['events']['enabled']) {
            $this->addChild($menu, $request, 'Event', 'tuna_news_list', 120, function ($request, $route) {
                return
                    preg_match_all('/tuna_news_/i', $request->get('_route')) &&
                    (
                        $request->attributes->get('newsType') == 'Event' ||
                        $request->attributes->get('news') instanceof Event
                    );
            }, array(
                'newsType' => 'Event'
            ));
        }

        if ($this->componentsConfig['categories']['enabled']) {
            $this->addChild($menu, $request, 'Categories', 'tuna_category_list', 400, function ($request, $route) {
                return preg_match_all('/tuna_category/i', $request->get('_route'));
            });
        }

        if ($this->componentsConfig['translations']['enabled']) {
            $this->addChild($menu, $request, 'Translations', 'tuna_translations', 500, function ($request, $route) {
                return preg_match_all('/thecodeine_translations/i', $request->get('_route'));
            });
        }

        return $menu;
    }

    /**
     * @return ItemInterface
     */
    protected function addChild($menu, $request, $label, $route, $position = null, callable $activeTest = null, $routeParameters = array())
    {
        $menu->addChild($this->createChild($request, $label, $route, $position, $activeTest, $routeParameters));

        return $menu;
    }

    /**
     * @return ItemInterface
     */
    protected function createChild($request, $label, $route, $position, $activeTest = null, $routeParameters = array())
    {
        if ($position === null) {
            $position = 200;
        }
        if ($activeTest == null) {
            $activeTest = function ($request, $route) {
                return $request->get('_route') === $route;
            };
        }
        $child = $this->factory->createItem($label, array(
            'route' => $route,
            'routeParameters' => $routeParameters,
            'attributes' => array(
                'class' => $activeTest($request, $route) ? 'active' : ''
            )
        ))->setExtra('position', $position);

        return $child;
    }
}
