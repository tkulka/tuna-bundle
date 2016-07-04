<?php

namespace TheCodeine\CategoryBundle\Controller;

use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

use TheCodeine\CategoryBundle\Entity\Category;
use TheCodeine\CategoryBundle\Form\CategoryType;

abstract class AbstractCategoryController extends Controller
{
    /**
     * @return Category
     */
    abstract public function getNewObject();

    /**
     * @return Category
     */
    abstract public function getNewFormType(Category $category = null);

    /**
     * @return string
     */
    abstract public function getRedirectUrl(Category $category = null);

    /**
     * @return EntityRepository
     */
    abstract public function getRepository();

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

    /**
     * @Route("/create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $entity = $this->getNewObject();

        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm($this->getNewFormType(), $entity);
        $form->add('save', 'submit');
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->getRedirectUrl($entity));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/{id}/edit")
     * @Template()
     */
    public function editAction(Request $request, Category $category)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm($this->getNewFormType(), $category);
        $form->add('save', 'submit');
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            return $this->redirect($this->getRedirectUrl($category));
        }

        return array(
            'entity' => $category,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request, Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);

        try {
            $em->flush();
        } catch (DBALException $e) {
            $translator = $this->get('translator.default');
            $errorMsg = $translator->trans('error.category.not_empty', array('%name%' => $category->getName()), 'validators');
            $this->get('session')->getFlashBag()->add('error', $errorMsg);
        }

        return $this->redirect($this->getRedirectUrl($category));
    }
}
