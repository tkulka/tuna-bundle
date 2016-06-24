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

    /**
     * @var String
     */
    private $enableTranslations;

    /**
     * @param FactoryInterface $factory
     * @param String $enableTranslations
     */
    public function __construct(FactoryInterface $factory, $enableTranslations)
    {
        $this->factory = $factory;
        $this->enableTranslations = $enableTranslations;
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

        $this->addChild($menu, $request, 'Pages', 'tuna_page_list', 100, function ($request, $route) {
            return preg_match_all('/tuna_page/i', $request->get('_route'));
        });
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

        if ($this->enableTranslations == 'true') {
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
