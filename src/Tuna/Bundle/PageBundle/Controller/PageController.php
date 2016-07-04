<?php

namespace TheCodeine\PageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use TheCodeine\PageBundle\Entity\AbstractPage;
use TheCodeine\PageBundle\Entity\Page;
use TheCodeine\PageBundle\Form\PageType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/page")
 */
class PageController extends AbstractPageController
{
    public function getNewPage()
    {
        return new Page();
    }

    public function getNewFormType(AbstractPage $page = null, $validate = true)
    {
        return new PageType($validate);
    }

    public function getRedirectUrl(AbstractPage $page = null)
    {
        return $this->generateUrl('tuna_page_list');
    }

    public function getRepository()
    {
        return $this->getDoctrine()->getRepository('TheCodeinePageBundle:Page');
    }

    /**
     * @Route("/list", name="tuna_page_list")
     * @Template()
     */
    public function listAction(Request $request)
    {
        return parent::listAction($request);
    }

    /**
     * @Route("/create", name="tuna_page_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route("/{id}/edit", name="tuna_page_edit", requirements={"id" = "\d+"})
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        return parent::editAction($request, $id);
    }

    /**
     *
     * @Route("/{id}/delete", name="tuna_page_delete", requirements={"id" = "\d+"})
     * @Template()
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::deleteAction($request, $id);
    }
}
