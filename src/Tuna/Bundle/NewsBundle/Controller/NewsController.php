<?php

namespace TheCodeine\NewsBundle\Controller;

use TheCodeine\NewsBundle\Entity\AbstractNews;
use TheCodeine\NewsBundle\Entity\Category;
use TheCodeine\NewsBundle\Form\CategoryType;
use TheCodeine\PageBundle\Controller\AbstractPageController;
use TheCodeine\PageBundle\Entity\AbstractPage;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/news")
 */
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
     * @Route("/{newsType}/list", name="tuna_news_list")
     * @Template()
     */
    public function listAction(Request $request)
    {
        return array(
            'entities' => $this->getRepository()->findAll(),
        );
    }

    /**
     * @Route("/create", name="tuna_news_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $newsType = $request->query->get('newsType');
        $validate = !$request->isXmlHttpRequest();
        $news = $this->get('tuna.news.factory')->getInstance($newsType);
        $form = $this->createForm($this->get('tuna.news.factory')->getFormInstance($news, $validate), $news);
        $form->add('save', 'submit');

        $result = $this->handleCreateForm($request, $form, $news);

        if (is_array($result)) {
            $result['newsType'] = $newsType;
        }

        return $result;
    }

    /**
     * @Route("/{id}/edit", name="tuna_news_edit", requirements={"id" = "\d+"})
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        return parent::editAction($request, $id);
    }

    /**
     *
     * @Route("/{id}/delete", name="tuna_news_delete", requirements={"id" = "\d+"})
     * @Template()
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::deleteAction($request, $id);
    }
}
