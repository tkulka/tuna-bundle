<?php

namespace TheCodeine\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;
use Doctrine\Common\Persistence\ObjectManager;

class Builder extends ContainerAware
{
    /**
     * @var \Knp\Menu\FactoryInterface
     */
    public $factory;

    /**
     * @var Translator
     */
    public $translatorInterface;


    /**
     * @param FactoryInterface $factory
     * @param TranslatorInterface $translatorInterface
     */
    public function __construct(FactoryInterface $factory, TranslatorInterface $translatorInterface)
    {
        $this->factory = $factory;
        $this->translatorInterface = $translatorInterface;
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

        $this->addNewsChild($menu, $request, "News");

        $menu->addChild($this->translatorInterface->trans('Pages'), array(
            'route' => 'tuna_page_list',
            'attributes' => array(
                "class" => preg_match_all('/tuna_page/i', $request->get('_route')) ? "active" : ""
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

        if (preg_match_all('/tuna_news/i', $request->get('_route'))) {
            $menu = $this->buildNewsSubmenu($menu, $request);
        }

        if (preg_match_all('/tuna_page/i', $request->get('_route'))) {
            $menu = $this->buildPageSubmenu($menu, $request);
        }

        return $menu;
    }

    public function buildPageSubmenu($menu, $request)
    {
        $menu->addChild($this->translatorInterface->trans('Create page'), array(
            'route' => 'tuna_page_create',
            'attributes' => array(
                "class" => $request->get('_route') === 'tuna_page_create' ? "active" : ""
            )
        ));

        return $menu;
    }

    public function buildNewsSubmenu($menu, $request)
    {
        $menu->addChild($this->translatorInterface->trans('Create ' . $request->get('_route_params')['newsType']), array(
            'route' => 'tuna_news_create',
            'routeParameters' => array('newsType' => $request->get('_route_params')['newsType'])
        ));

        return $menu;
    }

    public function addNewsChild($menu, $request, $type)
    {
        $menu->addChild($this->translatorInterface->trans($type), array(
            'route' => 'tuna_news_list',
            'routeParameters' => array('newsType' => $type),
            'attributes' => array(
                'class' => (
                    isset($request->get('_route_params')['newsType']) &&
                    $request->get('_route_params')['newsType'] == $type
                ) ? 'active' : '',
            )
        ));
    }
}
