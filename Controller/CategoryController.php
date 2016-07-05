<?php

namespace TheCodeine\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TheCodeine\CategoryBundle\Entity\Category;

/**
 * @Route("/category")
 */
class CategoryController extends \TheCodeine\CategoryBundle\Controller\CategoryController
{
    /**
     * @Route("/list", name="tuna_category_list")
     * @Template()
     */
    public function listAction(Request $request)
    {
        return parent::listAction($request);
    }

    /**
     * @Route("/create", name="tuna_category_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * @Route("/{id}/edit", name="tuna_category_edit")
     * @Template()
     */
    public function editAction(Request $request, Category $category)
    {
        return parent::editAction($request, $category);
    }

    /**
     * @Route("/{id}/delete", name="tuna_category_delete")
     */
    public function deleteAction(Request $request, Category $category)
    {
        return parent::deleteAction($request, $category);
    }
}
