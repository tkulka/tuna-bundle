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
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('TheCodeinePageBundle:Page');

        $page = $this->get('request')->get('page', 1);
        $limit = 10;

        $pages = $repository->findAll();

        $paginator =  $this->get('knp_paginator');
        $pagination = $paginator->paginate($pages, $page, $limit);

        return array(
            'pagination' => $pagination,
            'lp' => $page * $limit - $limit,
            'pagesList' => $pages
        );
    }
}