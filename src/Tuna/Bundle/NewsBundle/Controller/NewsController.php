<?php

namespace TheCodeine\NewsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TheCodeine\NewsBundle\Entity\AbstractNews;

/**
 * @Route("/news")
 */
class NewsController extends AbstractNewsController
{
    public function getNewNews($newsType)
    {
        return $this->get('the_codeine_news.factory')->getInstance($newsType);
    }

    public function getFormType(AbstractNews $abstractNews)
    {
        return $this->get('the_codeine_news.factory')->getFormInstance($abstractNews);
    }

    public function getRedirectUrl(Request $request, AbstractNews $abstractNews)
    {
        return $this->generateUrl('tuna_news_list', [
            'newsType' => $abstractNews->getType()
        ]);
    }

    public function getRepository()
    {
        return $this->getDoctrine()->getRepository(AbstractNews::class);
    }
}