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
        return $this->generateUrl('tuna_page_edit', array('id' => $page->getId()));
    }

    public function getRepository()
    {
        return $this->getDoctrine()->getRepository('TheCodeinePageBundle:Page');
    }
}
