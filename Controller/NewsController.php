<?php

namespace TheCodeine\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use TheCodeine\NewsBundle\Controller\NewsController as TunaNewsController;

/**
 * @Route("/news")
 */
class NewsController extends TunaNewsController
{
    const PAGINATE_LIMIT = 10;

    /**
     * @Route("/{newsType}/list", name="tuna_news_list")
     * @Template()
     */
    public function listAction(Request $request, $newsType = null)
    {
        $query = $this->getDoctrine()->getRepository('TheCodeineNewsBundle:AbstractNews')->getListQuery($newsType);
        $defaultSort = [
            'defaultSortFieldName' => 'n.createdAt',
            'defaultSortDirection' => 'DESC'
        ];

        $page = $request->query->get('page', 1);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, self::PAGINATE_LIMIT, $defaultSort);

        return [
            'offset' => $page * self::PAGINATE_LIMIT - self::PAGINATE_LIMIT,
            'newsType' => $newsType,
            'pagination' => $pagination,
        ];
    }
}
