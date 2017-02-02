<?php

namespace TheCodeine\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/")
 */
class DashboardController extends Controller
{
    const PAGINATE_LIMIT = 10;

    /**
     * @Route("", name="tuna_admin_dashboard")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $menuRepository = $this->getDoctrine()->getRepository('TheCodeineMenuBundle:Menu');

        $query = $menuRepository->getStandalonePagesPaginationQuery();
        $page = $request->get('page', 1);

        return [
            'menus' => $menuRepository->getMenuTree(null, false),
            'offset' => ($page - 1) * self::PAGINATE_LIMIT,
            'pagination' => $this->get('knp_paginator')->paginate($query, $page, self::PAGINATE_LIMIT),
        ];
    }
}
