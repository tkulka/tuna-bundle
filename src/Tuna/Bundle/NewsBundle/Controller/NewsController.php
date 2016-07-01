<?php

namespace TheCodeine\NewsBundle\Controller;

use TheCodeine\NewsBundle\Entity\AbstractNews;
use TheCodeine\NewsBundle\Entity\Category;
use TheCodeine\NewsBundle\Form\CategoryType;

use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\Query;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TheCodeine\PageBundle\Controller\AbstractPageController;
use TheCodeine\PageBundle\Entity\AbstractPage;

class NewsController extends AbstractPageController
{
    public function getNewPage()
    {
        return $this->get('tuna.news.factory')->getInstance();
    }

    public function getNewFormType(AbstractPage $news = null, $validate = true)
    {
        return $this->get('tuna.news.factory')->getFormInstance($news, $validate);
    }

    public function getRedirectUrl(AbstractPage $page = null)
    {
        return $this->generateUrl('tuna_news_list', array('newsType' => $page->getType()));
    }

    public function getRepository()
    {
        return $this->getDoctrine()->getRepository('TheCodeineNewsBundle:AbstractNews');
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
        $validate = !$request->isXmlHttpRequest();
        $news = $this->get('tuna.news.factory')->getInstance($newsType);
        $form = $this->createForm($this->get('tuna.news.factory')->getFormInstance($news, $validate), $news);

        $result = $this->handleCreateForm($request, $form, $news);

        if (is_array($result)) {
            $result['newsType'] = $newsType;
        }

        return $result;
    }
}
