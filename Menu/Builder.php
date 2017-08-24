<?php

namespace TunaCMS\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class Builder
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var array
     */
    private $componentsConfig;

    /**
     * Builder constructor.
     *
     * @param FactoryInterface $factory
     * @param array $componentsConfig
     */
    public function __construct(FactoryInterface $factory, array $componentsConfig)
    {
        $this->factory = $factory;
        $this->componentsConfig = $componentsConfig;
    }

    /**
     * @param RequestStack $requestStack
     *
     * @return ItemInterface
     */
    public function getTopMenu(RequestStack $requestStack)
    {
        $menu = $this->buildTopMenu($requestStack->getCurrentRequest());
        $this->reorderMenu($menu);

        return $menu;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return ItemInterface
     */
    protected function reorderMenu(ItemInterface $menu)
    {
        $order = [];
        foreach ($menu->getChildren() as $label => $item) {
            $order[$label] = $item->getExtra('position');
        }
        asort($order);
        $menu->reorderChildren(array_keys($order));

        return $menu;
    }

    /**
     * @param Request $request
     *
     * @return ItemInterface
     */
    protected function buildTopMenu(Request $request)
    {
        $menu = $this->factory->createItem('root', [
            'childrenAttributes' => ['class' => 'nav']
        ]);

        $this->addChild($menu, $request, 'menu.dashboard', 'tuna_cms_dashboard', 0, function (Request $request, $route) {
            return preg_match_all('/tuna_cms_dashboard/i', $request->get('_route'));
        });

        if ($this->componentsConfig['categories']['enabled']) {
            $this->addChild($menu, $request, 'menu.categories', 'tuna_category_list', 400, function (Request $request, $route) {
                return preg_match_all('/tuna_category_/i', $request->get('_route'));
            });
        }

        if ($this->componentsConfig['translations']['enabled']) {
            $this->addChild($menu, $request, 'menu.translations', 'lexik_translation_grid', 500, function (Request $request, $route) {
                return preg_match_all('/lexik_translation/i', $request->get('_route'));
            });
        }

        return $menu;
    }

    /**
     * @param ItemInterface $menu
     * @param Request $request
     * @param string $label
     * @param string $route
     * @param null|int $position
     * @param callable|null $activeTest
     * @param array $routeParameters
     *
     * @return mixed
     */
    protected function addChild(ItemInterface $menu, Request $request, $label, $route, $position = null, callable $activeTest = null, $routeParameters = [])
    {
        $menu->addChild($this->createChild($request, $label, $route, $position, $activeTest, $routeParameters))->setExtra('translation_domain', 'tuna_admin');

        return $menu;
    }

    /**
     * @param Request $request
     * @param string $label
     * @param string $route
     * @param int $position
     * @param null|callable $activeTest
     * @param array $routeParameters
     *
     * @return ItemInterface
     */
    protected function createChild(Request $request, $label, $route, $position = 200, callable $activeTest = null, $routeParameters = [])
    {
        if ($activeTest == null) {
            $activeTest = function (Request $request, $route) {
                return $request->get('_route') === $route;
            };
        }

        $child = $this->factory->createItem($label, [
            'route' => $route,
            'routeParameters' => $routeParameters,
            'attributes' => [
                'class' => $activeTest($request, $route) ? 'active' : ''
            ]
        ])->setExtra('position', $position);

        return $child;
    }
}
