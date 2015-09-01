<?php

namespace TheCodeine\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use TheCodeine\NewsBundle\Entity\Category;

class DefaultController extends Controller
{
    /**
     * @Route("/admin/", name="thecodeine_admin_homepage")
     * @Template()
     */
    public function indexAction()
    {
        $categoryId = $this->get('request')->get('cid',null);

        if(!$categoryId) {
            $category = $this->getDoctrine()->getRepository('TheCodeineNewsBundle:Category')->findOneBy(array(
                'lvl' => 0
            ));

            if(!$category) {
                return $this->createNotFoundException('No category found');
            }

            $categoryId = $category->getId();
        }

        return $this->forward('TheCodeineAdminBundle:Default:category', array(
            'cid' => $categoryId
        ));

    }

    /**
     * @Route("/admin/cid/{cid}", name="thecodeine_admin_category")
     * @ParamConverter("category", class="TheCodeineNewsBundle:Category", options={"id" = "cid"})
     * @Template()
     */
    public function categoryAction(Category $category)
    {

        //we need to select proper subcategory while being in top menu
        if ($category->isSinglePage()) {
            $page = $this->getDoctrine()->getRepository('TheCodeinePageBundle:Page')->findOneByCategory($category);
            return $this->redirect($this->generateUrl('thecodeine_admin_page_edit', array(
                'id' => $page->getId(),
            )));
        }
        if($category->getLvl() == 0 ) {
            return $this->forward('TheCodeineAdminBundle:Default:category', array(
                'cid' => $category->getChildren()->first()->getId()
            ));
        } else if ($category->getLvl() == 1 && $category->isGroup() && count($category->getChildren())) {
            return $this->forward('TheCodeineAdminBundle:Default:category', array(
                'cid' => $category->getChildren()->first()->getId()
            ));
        } else if ( $category->isGroup() && !$category->getHasNews() && count($category->getChildren())) {

            return $this->forward('TheCodeineAdminBundle:Default:category', array(
                'cid' => $category->getChildren()->first()->getId()
            ));
        }

        if($category->getHasNews()) {
            return $this->redirect($this->generateUrl('thecodeine_admin_news_list', array('cid' => $category->getId())));
        }

        $pages = array();
        if(!$category->getHasNews()) {
            $pages = $this->getDoctrine()
                ->getRepository('TheCodeinePageBundle:Page')
                ->findBy(array('category' => $category));
        }

        return array(
            'category'  => $category,
            'pages'     => $pages
        );
    }
}
