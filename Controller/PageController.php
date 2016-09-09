<?php

namespace TheCodeine\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/page")
 */
class PageController extends \TheCodeine\PageBundle\Controller\PageController
{
    /**
     *
     * @Route("/list", name="tuna_page_list")
     * @Template()
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('TheCodeinePageBundle:Page')->getListQuery();
        $menuMap = $em->getRepository('TheCodeineMenuBundle:Menu')->getPageMap();
        $page = $request->get('page', 1);
        $limit = 10;

        return array(
            'pagination' => $this->get('knp_paginator')->paginate($query, $page, $limit),
            'offset' => ($page - 1) * $limit,
            'menuMap' => $menuMap,
        );
    }

    /**
     * @Route("/create", name="tuna_page_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted('create', 'pages');

        return parent::createAction($request);
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
     *
     * @Route("/{id}/delete", name="tuna_page_delete", requirements={"id" = "\d+"})
     * @Template()
     */
    public function deleteAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('delete', 'pages');

        return parent::deleteAction($request, $id);
    }
}
