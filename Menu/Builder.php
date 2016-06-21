<?php

namespace TheCodeine\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TheCodeine\NewsBundle\Entity\Category;

class Builder
{
    /**
     * @var \Knp\Menu\FactoryInterface
     */
    private $factory;

    /**
     * @var Translator
     */
    private $translatorInterface;

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
     * @return \Knp\Menu\ItemInterface
     */
    public function buildTopMenuStatic(Request $request)
    {
        $menu = $this->factory->createItem('root', array(
            'childrenAttributes' => array('class' => 'nav')
        ));

        $menu->addChild($this->translatorInterface->trans('Pages'), array(
            'route' => 'tuna_page_list',
            'attributes' => array(
                "class" => preg_match_all('/thecodeine_page/i', $request->get('_route')) ? "active" : ""
            )
        ));

        $menu->addChild($this->translatorInterface->trans('News'), array(
            'route' => 'tuna_news_list',
            'attributes' => array(
                "class" => preg_match_all('/thecodeine_news/i', $request->get('_route')) ? "active" : ""
            )
        ));
        
        if($this->enableTranslations == 'true') {
            $menu->addChild($this->translatorInterface->trans('Translations'), array(
                'route' => 'tuna_translations',
                'attributes' => array(
                    "class" => preg_match_all('/thecodeine_translations/i', $request->get('_route')) ? "active" : ""
                )
            ));
        }

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
        $menu->addChild($this->translatorInterface->trans('List of pages'), array(
            'route' => 'tuna_page_list',
            'attributes' => array(
                "class" => $request->get('_route') === 'tuna_page_list' ? "active" : ""
            )
        ));
        $menu->addChild($this->translatorInterface->trans('Create page'), array(
            'route' => 'tuna_page_create',
            'attributes' => array(
                "class" => $request->get('_route') === 'tuna_page_create' ? "active" : ""
            )
        ));

        return $menu;
    }

    private function buildNewsSubmenu($menu, $request)
    {
        $menu->addChild($this->translatorInterface->trans('List of news'), array(
            'route' => 'tuna_news_list',
            'attributes' => array(
                "class" => $request->get('_route') === 'tuna_news_list' ? "active" : ""
            )
        ));
        $menu->addChild($this->translatorInterface->trans('Create news'), array(
            'route' => 'tuna_news_create',
            'attributes' => array(
                "class" => $request->get('_route') === 'tuna_news_create' ? "active" : ""
            )
        ));

        return $menu;
    }
}
