<?php

namespace TheCodeine\AdminBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use TheCodeine\PageBundle\Controller\PageController as Controller;
use TheCodeine\MenuBundle\Entity\Menu;
use TheCodeine\PageBundle\Entity\AbstractPage;
use TheCodeine\PageBundle\Entity\Page;

/**
 * @Route("/page")
 */
class PageController extends Controller
{
    const PAGINATE_LIMIT = 10;

    /**
     * @param AbstractPage|null $page
     * @param Request|null $request
     *
     * @return string
     */
    public function getRedirectUrl(AbstractPage $page = null, Request $request = null)
    {
        if ($request && $request->query->get('redirect') == 'dashboard') {
            return $this->generateUrl('tuna_admin_dashboard');
        }

        return parent::getRedirectUrl($page);
    }

    /**
     * @Route("/list", name="tuna_page_list")
     * @Template()
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('TheCodeinePageBundle:Page')->getListQuery();
        $menuMap = $em->getRepository('TheCodeineMenuBundle:Menu')->getPageMap();
        $page = $request->get('page', 1);

        return [
            'offset' => ($page - 1) * self::PAGINATE_LIMIT,
            'menuMap' => $menuMap,
            'pagination' => $this->get('knp_paginator')->paginate($query, $page, self::PAGINATE_LIMIT),
        ];
    }

    /**
     * @Route("/create", name="tuna_page_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted('create', 'pages');

        $page = $this->getNewPage();
        $form = $this->createForm($this->getNewFormType($page, !$request->isXmlHttpRequest()), $page);
        $form->add('save', 'submit');
        if (($parentId = $request->query->get('menuParentId'))) {
            $menuParent = $this->getDoctrine()->getManager()->getReference('TheCodeineMenuBundle:Menu', $parentId);
        }

        $return = $this->handleCreateForm($request, $form, $page);
        if (
            $form->isValid()
            && !$request->isXmlHttpRequest()
            && isset($menuParent)
        ) {
            $this->createMenuForPage($menuParent, $page);
        }

        return $return;
    }

    /**
     * @Route("/{id}/edit", name="tuna_page_edit", requirements={"id" = "\d+"})
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        return parent::editAction($request, $id);
    }

    /**
     * @Route("/{id}/delete", name="tuna_page_delete", requirements={"id" = "\d+"})
     * @Template()
     */
    public function deleteAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('delete', 'pages');

        return parent::deleteAction($request, $id);
    }

    /**
     * @Route("/add-to-menu", name="tuna_page_add_to_menu")
     */
    public function createMenuItemAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getReference('TheCodeinePageBundle:Page', $request->request->get('pageId'));
        $menuParent = $em->getReference('TheCodeineMenuBundle:Menu', $request->request->get('menuParentId'));

        $this->createMenuForPage($menuParent, $page);

        return new JsonResponse('ok');
    }

    /**
     * @param Menu $menuParent
     * @param Page $page
     */
    private function createMenuForPage(Menu $menuParent, Page $page)
    {
        $em = $this->getDoctrine()->getManager();
        $menu = new Menu('tmp');
        $menu->setPage($page);
        $menu->setParent($menuParent);
        $em->persist($menu);
        $em->flush();
    }
}
