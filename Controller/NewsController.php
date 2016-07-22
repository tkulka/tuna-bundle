<?php

namespace TheCodeine\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\JsonResponse;
use TheCodeine\ImageBundle\Entity\Image;
use TheCodeine\ImageBundle\Form\ImageRequestType;

/**
 * @Route("/news")
 */
class NewsController extends \TheCodeine\NewsBundle\Controller\NewsController
{
    /**
     * @Route("/{newsType}/list", name="tuna_news_list")
     * @Template()
     */
    public function listAction(Request $request, $newsType = null)
    {
        $query = $this->getDoctrine()->getRepository('TheCodeineNewsBundle:AbstractNews')->getListQuery($newsType);
        $defaultSort = array(
            'defaultSortFieldName' => 'n.createdAt',
            'defaultSortDirection' => 'DESC'
        );

        $page = $request->query->get('page', 1);
        $limit = 10;

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, $limit, $defaultSort);

        return array(
            'pagination' => $pagination,
            'offset' => $page * $limit - $limit,
            'newsType' => $newsType,
        );
    }
}
