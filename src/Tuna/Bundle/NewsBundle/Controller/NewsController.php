<?php

namespace TheCodeine\NewsBundle\Controller;

use TheCodeine\NewsBundle\Entity\BaseNews;
use TheCodeine\NewsBundle\Entity\Category;
use TheCodeine\NewsBundle\Form\CategoryType;

use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\Query;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TheCodeine\PageBundle\Controller\BasePageController;
use TheCodeine\PageBundle\Entity\BasePage;

class NewsController extends BasePageController
{
    public function getNewPage()
    {
        return new BaseNews();
    }

    public function getNewFormType(BasePage $news = null)
    {
        return $this->get('tuna.news.factory')->getFormInstance($news);
    }

    public function getRedirectUrl(BasePage $page = null)
    {
        return $this->generateUrl('tuna_news_list', array('newsType' => $page->getType()));
    }

    public function getRepository()
    {
        return $this->getDoctrine()->getRepository('TheCodeineNewsBundle:BaseNews');
    }

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
        return array(
            'entities' => $this->getRepository()->findAll(),
        );
    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $newsType = $request->query->get('newsType');
        $news = $this->get('tuna.news.factory')->getInstance($newsType);
        $form = $this->createForm($this->get('tuna.news.factory')->getFormInstance($news), $news);

        $result = $this->handleCreateForm($request, $form, $news);

        if (is_array($result)) {
            $result['newsType'] = $newsType;
        }

        return $result;
    }
}
