<?php

namespace TheCodeine\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use TheCodeine\NewsBundle\Controller\NewsController as Controller;

class NewsController extends Controller
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
        $page = $this->get('request')->query->get('page', 1);
        $limit = 10;

        $categoryId = $request->get('cid');
        $em = $this->getDoctrine()->getManager();
        if($categoryId) {
            $query = $em
                ->createQuery('SELECT n FROM TheCodeineNewsBundle:News n WHERE n.category = :category ORDER BY n.createdAt DESC')
                ->setParameter('category', $categoryId)
            ;
        } else {
            $query = $em
                ->createQuery('SELECT n FROM TheCodeineNewsBundle:News n ORDER BY n.createdAt DESC')
            ;
        }
        $pages = $query->getResult();

        $paginator =  $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            $limit
        );

        return array(
            'pagination' => $pagination,
            'lp' => $page * $limit - $limit,
            'newsList' => $pages
        );
    }
}