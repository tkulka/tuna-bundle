<?php

namespace TheCodeine\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class NewsController extends \TheCodeine\NewsBundle\Controller\NewsController
{
    /**
     *
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $query = $this->getDoctrine()->getManager()->getRepository('TheCodeineNewsBundle:News')->getListQuery();
        $page = $request->get('page', 1);
        $limit = 10;
        $sort = array(
            'defaultSortFieldName' => 'p.createdAt',
            'defaultSortDirection' => 'DESC',
        );

        return array(
            'pagination' => $this->get('knp_paginator')->paginate($query, $page, $limit, $sort),
            'offset' => ($page - 1) * $limit,
        );
    }
}
