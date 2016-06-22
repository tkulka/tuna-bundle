<?php

namespace TheCodeine\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;
use Doctrine\Common\Persistence\ObjectManager;

class Builder
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var Translator
     */
    protected $translatorInterface;

    /**
     * @var String
     */
    private $enableTranslations;

    /**
     * @param FactoryInterface $factory
     * @param TranslatorInterface $translatorInterface
     * @param String $enableTranslations
     */
    public function __construct(FactoryInterface $factory, TranslatorInterface $translatorInterface, $enableTranslations)
    {
        $this->factory = $factory;
        $this->translatorInterface = $translatorInterface;
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
     * @param Request $request
     * @return ItemInterface
     */
    public function getSubmenu(Request $request)
    {
        $menu = $this->buildSubMenu($request);
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
        $this->addChild($menu, $request, 'News', 'tuna_news_list', 101, function ($request, $route) {
            return preg_match_all('/tuna_news/i', $request->get('_route'));
        });

        if ($this->enableTranslations == 'true') {
            $this->addChild($menu, $request, 'Translations', 'tuna_translations', 500, function ($request, $route) {
                return preg_match_all('/thecodeine_translations/i', $request->get('_route'));
            });
        }

        return $menu;
    }

    /**
     * @param Request $request
     * @return ItemInterface
     */
    protected function buildSubMenu(Request $request)
    {
        $menu = $this->factory->createItem('root', array(
            'childrenAttributes' => array('class' => 'nav')
        ));

        if (preg_match_all('/tuna_page/i', $request->get('_route'))) {
            $this->addChild($menu, $request, 'Create page', 'tuna_page_create');
        }

        if (preg_match_all('/tuna_news/i', $request->get('_route'))) {
            $this->addChild($menu, $request, 'Create news', 'tuna_news_create');
        }

        return $menu;
    }

    /**
     * @return ItemInterface
     */
    protected function addChild($menu, $request, $label, $route, $position = null, callable $activeTest = null)
    {
        if ($position === null) {
            $position = 200;
        }
        if ($activeTest == null) {
            $activeTest = function ($request, $route) {
                return $request->get('_route') === $route;
            };
        }
        $child = $this->factory->createItem($this->translatorInterface->trans($label), array(
            'route' => $route,
            'attributes' => array(
                'class' => $activeTest($request, $route) ? 'active' : ''
            )
        ))->setExtra('position', $position);
        $menu->addChild($child);

        return $menu;
    }
}
