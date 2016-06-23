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
     * @param string $newsType
     *
     * @return array
     */
    public function listAction(Request $request, $newsType)
    {
        $em = $this->getDoctrine()->getManager();
        $dql   = "SELECT n FROM TheCodeineNewsBundle:$newsType n";
        $query = $em->createQuery($dql);
        $defaultSort = array(
            'defaultSortFieldName' => 'n.createdAt',
            'defaultSortDirection' => 'DESC'
        );

        $page = $request->query->get('page', 1);
        $limit = 10;

        $paginator =  $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, $limit, $defaultSort);

        return array(
            'pagination' => $pagination,
            'offset' => $page * $limit - $limit,
            'newsType' => $newsType,
        );
    }
}
