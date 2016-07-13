<?php

namespace TheCodeine\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @Route("/create", name="tuna_news_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted('create', strtolower($request->query->get('newsType')));

        return parent::createAction($request);
    }

    /**
     *
     * @Route("/{id}/delete", name="tuna_news_delete", requirements={"id" = "\d+"})
     * @Template()
     */
    public function deleteAction(Request $request, $id)
    {
        $page = $this->getRepository()->find($id);
        $this->denyAccessUnlessGranted('delete', strtolower($page->getType()));

        return parent::handleDeletion($page);
    }
}
