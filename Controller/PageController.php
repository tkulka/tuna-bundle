<?php

namespace TheCodeine\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use TheCodeine\MenuBundle\Entity\MenuInterface;
use TheCodeine\PageBundle\Controller\PageController as TunaPageController;
use TunaCMS\PageComponent\Model\AbstractPage;

/**
 * @Route("/page")
 */
class PageController extends TunaPageController
{
    const PAGINATE_LIMIT = 10;

    /**
     * {@inheritDoc}
     */
    public function getRedirectUrl(Request $request)
    {
        if ($request->query->get('redirect') == 'dashboard') {
            return $this->generateUrl('tuna_admin_dashboard');
        }

        return parent::getRedirectUrl($request);
    }

    /**
     * @Route("/list", name="tuna_page_list")
     * @Template()
     */
    public function listAction(Request $request)
    {
        $query = $this->get('the_codeine_page.manager')->getListQuery();
        $menuMap = $this->get('the_codeine_menu.manager')->getPageMap();
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
        $form = $this->createForm($this->getFormType(), $page);

        // TODO: Move this to twig
        $form->add('save', SubmitType::class, [
            'label' => 'ui.form.labels.save'
        ]);

        if (($parentId = $request->query->get('menuParentId'))) {
            $menuClass = $this->get('the_codeine_menu.manager')->getClassName();
            $menuParent = $this->getDoctrine()->getManager()->getReference($menuClass, $parentId);
        }

        $return = $this->handleCreate($request, $form, $page);

        if ($form->isValid() && !$request->isXmlHttpRequest() && isset($menuParent)) {
            $this->createMenuForPage($menuParent, $page);
        }

        return $return;
    }

    /**
     * @Route("/{id}/delete", name="tuna_page_delete", requirements={"id" = "\d+"})
     * @Template()
     */
    public function deleteAction(Request $request, AbstractPage $page)
    {
        $this->denyAccessUnlessGranted('delete', 'pages');

        return parent::deleteAction($request, $page);
    }

    /**
     * @Route("/add-to-menu", name="tuna_page_add_to_menu")
     */
    public function createMenuItemAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getReference($this->get('the_codeine_page.factory')->getModelClass(), $request->request->get('pageId'));

        $menuClass = $this->get('the_codeine_menu.manager')->getClassName();
        $menuParent = $em->getReference($menuClass, $request->request->get('menuParentId'));

        $this->createMenuForPage($menuParent, $page);

        return new JsonResponse('ok');
    }

    /**
     * @param MenuInterface $menuParent
     * @param AbstractPage $page
     */
    private function createMenuForPage(MenuInterface $menuParent, AbstractPage $page)
    {
        $em = $this->getDoctrine()->getManager();
        $menu = $this->get('the_codeine_menu.manager')->getMenuInstance();
        $menu
            ->setLabel('tmp')
            ->setPage($page)
            ->setParent($menuParent);
        $em->persist($menu);
        $em->flush();
    }
}
