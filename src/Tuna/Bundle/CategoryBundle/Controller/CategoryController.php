<?php

namespace TunaCMS\Bundle\CategoryBundle\Controller;

use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

use TunaCMS\Bundle\CategoryBundle\Entity\Category;
use TunaCMS\Bundle\CategoryBundle\Form\CategoryType;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractCategoryController
{
    public function getNewObject()
    {
        return new Category();
    }

    public function getNewFormType(Category $category = null)
    {
        return new CategoryType();
    }

    public function getRedirectUrl(Category $category = null)
    {
        return $this->generateUrl('tuna_category_list');
    }

    public function getRepository()
    {
        return $this->getDoctrine()->getRepository('TunaCMSCategoryBundle:Category');
    }

    /**
     * @Route("/list")
     * @Template()
     */
    public function listAction(Request $request)
    {
        $categories = $this->getRepository()->findAll();
        $groupedCategories = [];

        foreach ($categories as $category) {
            $groupedCategories[$category->getType()][] = $category;
        }

        return [
            'groupedCategories' => $groupedCategories,
        ];
    }
}
