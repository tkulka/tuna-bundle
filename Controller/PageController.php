<?php

namespace TheCodeine\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class PageController extends \TheCodeine\PageBundle\Controller\PageController
{
    /**
     *
     * @Template()
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $query = $this->getDoctrine()->getManager()->getRepository('TheCodeinePageBundle:Page')->getListQuery();
        $page = $request->get('page', 1);
        $limit = 10;

        return array(
            'pagination' => $this->get('knp_paginator')->paginate($query, $page, $limit),
            'offset' => ($page - 1) * $limit,
        );
    }
}
