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
        $em = $this->getDoctrine()->getManager();
        $dql   = "SELECT n FROM TheCodeineNewsBundle:News n";
        $query = $em->createQuery($dql);
        $defaultSort = array('defaultSortFieldName' => 'n.createdAt', 'defaultSortDirection' => 'desc');

        $categoryId = $request->get('cid');
        $page = $request->query->get('page', 1);
        $limit = 10;

        if ($categoryId) {
            $query
                ->where('n.category = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }

        $paginator =  $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, $limit, $defaultSort);

        return array(
            'pagination' => $pagination,
            'lp' => $page * $limit - $limit,
        );
    }
}
