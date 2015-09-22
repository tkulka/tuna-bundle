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
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $em;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $categoryRepository;

    /**
     * @param FactoryInterface $factory
     * @param ObjectManager $manager
     */
    public function __construct(FactoryInterface $factory, ObjectManager $manager)
    {
        $this->em                   = $manager;
        $this->factory              = $factory;
        $this->categoryRepository   = $this->em->getRepository('TheCodeineNewsBundle:Category');
    }

    /**
     * @param Category $category
     * @param mixed $level
     * @return Category
     */
    private function getParentCategory(Category $category, $level = false)
    {
        while(1) {
            if($category->getLvl() > $level && $level !== false) {
                $category = $category->getParent();
                continue;
            }
            return $category;
        }
    }

    /**
     * @param $category
     * @return array
     */
    private function getStaticPages($category)
    {
        return $this->em->getRepository('TheCodeinePageBundle:Page')->findBy(array('category'=>$category));
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
                "class" => $request->get('_route') == "thecodeine_page_list" ? "active" : ""
            )
        ));

        $menu->addChild('Newsy', array(
            'route' => 'thecodeine_news_list',
            'attributes' => array(
                "class" => $request->get('_route') == "thecodeine_news_list" ? "active" : ""
            )
        ));

        return $menu;
    }

    /**
     * @param Request $request
     * @return \Knp\Menu\ItemInterface
     */
    public function buildSubmenu(Request $request)
    {
        //check if parent level 2 is group category (used to render in select)
        $requestCategory     = $this->categoryRepository->findOneById($request->get('cid',1));
        $selectedCategoryTop = $this->getParentCategory($requestCategory, 2);
        $selectedCategory    = $this->getParentCategory($requestCategory, 3);

        if ($selectedCategoryTop->getParent() && !$selectedCategoryTop->getParent()->isGroup()) {
            $selectedCategoryTop = $this->getParentCategory($this->categoryRepository->findOneById($request->get('cid',1)), 1);
            $selectedCategory    = $this->getParentCategory($requestCategory, 2);
        }

        // we need some if statements to render proper submenu while editing top category static page
        if($selectedCategory->getLvl() === 0) {
            $selectedCategory = $selectedCategory->getChildren()->first();
        }
        if($selectedCategory->isGroup() && $selectedCategory->getLvl() == 1 && count($selectedCategory->getChildren())) {
            $selectedCategoryTop = $selectedCategory;
            $selectedCategory = $selectedCategory->getChildren()->first();
        }

        if($selectedCategory && !$selectedCategory->getHasNews() && $selectedCategory->getLvl() == 2 && count($selectedCategory->getChildren())) {
            $selectedCategoryTop = $selectedCategory;
            $selectedCategory = $selectedCategory->getChildren()->first();
        }

        return $this->buildSubmenuForCategory($selectedCategoryTop, $selectedCategory, $requestCategory, $request->get('id'));
    }

    /**
     * @param Category $selectedCategory
     * @return \Knp\Menu\ItemInterface
     */
    private function buildSubmenuForCategory(Category $selectedCategoryTop, Category $selectedCategory, Category $requestCategory, $requestItemId)
    {
        $parentCategory   = $selectedCategoryTop->getLvl() == 0 ? $selectedCategoryTop : $selectedCategoryTop->getParent();


        if($selectedCategory->getId() == $selectedCategoryTop->getId() && $selectedCategory->getLvl()>0) {
            $selectedCategoryTop = $selectedCategory->getParent();
        }
        $menu = $this->factory->createItem('root', array(
            'childrenAttributes' => array('class' => 'nav')
        ));

        if($parentCategory->isGroup()) {

//            $groupMenu = $menu->addChild($parentCategory->getName(), array(
//                'route' => 'thecodeine_admin_category',
//                'routeParameters' => array('cid' => $parentCategory->getId()),
//                'attributes' => array("template" => "select")
//            ));
//
//            foreach($parentCategory->getChildren() as $child) {
//
//                $groupMenu->addChild($child->getName(), array(
//                    'route' => 'thecodeine_admin_category',
//                    'routeParameters' => array('cid' => $child->getId()),
//                    'attributes' => array("selected" => $selectedCategory->getParent() == $child && $requestCategory === $selectedCategory ? "selected" : "")
//                ));
//            }
//
//            foreach($selectedCategoryTop->getChildren() as $child) {
//                $menu->addChild($child->getName(), array(
//                    'route' => 'thecodeine_admin_category',
//                    'routeParameters' => array('cid' => $child->getId()),
//                    'attributes' => array(
//                        "class" => $selectedCategory == $child && $requestCategory === $selectedCategory ? "active" : "",
//                    )
//                ));
//            }

        } else {
            $children = array();
            foreach($parentCategory->getChildren() as $child){
                $children[] = $child;
            };
            uasort($children, function($a, $b){
                if($a->hasNews() == $b->hasNews()) {
                    return 0;
                }
                return $a->hasNews() ? -1 : 1;
            });
            foreach($children as $child) {
                if($child->hasNews()) {
                    $menu->addChild($child->getName(), array(
                        'route' => 'thecodeine_news_list',
                        'routeParameters' => array('cid' => $child->getId()),
                        'attributes' => array(
                            "class" => $selectedCategory == $child && $requestCategory === $selectedCategory ? "active" : "",
                        )
                    ));
                } else {
//                    $menu->addChild($child->getName(), array(
//                        'route' => 'thecodeine_admin_category',
//                        'routeParameters' => array('cid' => $child->getId()),
//                        'attributes' => array(
//                            "class" => $selectedCategory == $child && $requestCategory === $selectedCategory ? "active" : "",
//                        )
//                    ));
                }
            }
        }

        /*
         * Construction of menu is that we do not show static pages in submenu for page from top category
         * request category come from page that we currently edit
         */
        if($requestCategory->getLvl() === 0 && $requestItemId) {
            return $menu;
        }

        foreach($this->getStaticPages($selectedCategoryTop) as $page) {
            $menu->addChild($page->getTitle(), array(
                'route' => 'thecodeine_page_edit',
                'routeParameters' => array('id' => $page->getId()),
                'attributes' => array(
                    "class" => $selectedCategory == $page->getCategory() && $requestItemId == $page->getId() ? "active" : "",
                )
            ));
        }

        return $menu;
    }

}