<?php

namespace TheCodeine\PageBundle\Controller;

use TheCodeine\PageBundle\Entity\BasePage;
use TheCodeine\PageBundle\Entity\Page;
use TheCodeine\PageBundle\Form\PageType;

class PageController extends BasePageController
{
    public function getNewPage()
    {
        return new Page();
    }

    public function getNewFormType(BasePage $page = null)
    {
        return new PageType();
    }

    public function getRedirectUrl(BasePage $page = null)
    {
        return $this->generateUrl('tuna_page_list');
    }

    public function getRepository()
    {
        return $this->getDoctrine()->getRepository('TheCodeinePageBundle:Page');
    }
}
