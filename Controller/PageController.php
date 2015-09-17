<?php

namespace TheCodeine\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use TheCodeine\PageBundle\Controller\PageController as Controller;

class PageController extends Controller
{
    /**
     *
     * @Template()
     *
     * @return array
     */
    public function listAction()
    {
        $page = $this->get('request')->query->get('page', 1);
        $limit = 10;
        $query = $this->getDoctrine()->getManager()->getRepository("TheCodeinePageBundle:Page")->findAll();

        $paginator =  $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            $limit
        );

        return array(
            'pagination' => $pagination,
            'lp' => $page * $limit - $limit,
            'pagesList' => $query
        );
    }
}