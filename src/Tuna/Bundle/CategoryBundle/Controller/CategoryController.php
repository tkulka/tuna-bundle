<?php

namespace TheCodeine\CategoryBundle\Controller;

use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use TheCodeine\CategoryBundle\Entity\Category;
use TheCodeine\CategoryBundle\Form\CategoryType;

class CategoryController extends Controller
{
    /**
     * @Template()
     */
    public function listAction(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository('TheCodeineCategoryBundle:Category')->findAll();
        $groupedCategories = array();

        foreach ($categories as $category) {
            $groupedCategories[$category->getType()][] = $category;
        }

        return array(
            'groupedCategories' => $groupedCategories,
        );
    }

    /**
     * @Template()
     */
    public function editAction(Request $request, Category $category)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new CategoryType(), $category);
        $form->add('save', 'submit');
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('tuna_category_list');
        }

        return array(
            'entity' => $category,
            'form' => $form->createView(),
        );
    }

    public function deleteAction(Request $request, Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);

        try {
            $em->flush();
        } catch (DBALException $e) {
            $translator = $this->get('translator.default');
            $errorMsg = $translator->trans('error.category.not_empty', array('%name%' => $category->getName()), 'errors');
            $this->get('session')->getFlashBag()->add('error', $errorMsg);
        }

        return $this->redirectToRoute('tuna_category_list');
    }
}
