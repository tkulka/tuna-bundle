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
        $repository = $em->getRepository('TheCodeineNewsBundle:News');

        $categoryId = $request->get('category');
        $page = $request->get('page', 1);
        $limit = 10;

        $query = $repository->createQueryBuilder('n');

        if ($categoryId) {
            $query
                ->where('n.category = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }

        $query = $query->getQuery();
        $news = $query->getResult();

        $paginator =  $this->get('knp_paginator');
        $pagination = $paginator->paginate($news, $page, $limit);

        return array(
            'pagination' => $pagination,
            'lp' => $page * $limit - $limit,
            'newsList' => $news
        );
    }
}
