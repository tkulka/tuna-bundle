<?php

namespace TheCodeine\CategoryBundle\Controller;

use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

use TheCodeine\CategoryBundle\Entity\Category;
use TheCodeine\CategoryBundle\Form\CategoryType;

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
        return $this->getDoctrine()->getRepository('TheCodeineCategoryBundle:Category');
    }

    /**
     * @Route("/list")
     * @Template()
     */
    public function listAction(Request $request)
    {
        $categories = $this->getRepository()->findAll();
        $groupedCategories = array();

        foreach ($categories as $category) {
            $groupedCategories[$category->getType()][] = $category;
        }

        return array(
            'groupedCategories' => $groupedCategories,
        );
    }
}
