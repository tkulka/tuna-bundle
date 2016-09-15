<?php

namespace TheCodeine\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/dashboard")
 */
class DashboardController extends Controller
{
    /**
     * @Route("", name="tuna_admin_dashboard")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $menuRepository = $this->getDoctrine()->getRepository('TheCodeineMenuBundle:Menu');

        $query = $menuRepository->getStandalonePagesPaginationQuery();
        $page = $request->get('page', 1);
        $limit = 10;

        return array(
            'menus' => $menuRepository->getMenuTree(null, false),
            'offset' => ($page - 1) * $limit,
            'pagination' => $this->get('knp_paginator')->paginate($query, $page, $limit, array(
                'distinct' => false
            )),
        );
    }
}
