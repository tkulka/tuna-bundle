<?php

namespace TunaCMS\Bundle\CategoryBundle\Controller;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

use TunaCMS\Bundle\CategoryBundle\Entity\Category;
use TunaCMS\Bundle\CategoryBundle\Form\CategoryType;

abstract class AbstractCategoryController extends Controller
{
    /**
     * @return Category
     */
    abstract public function getNewObject();

    /**
     * @param Category|null $category
     *
     * @return CategoryType
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
        return [
            'categories' => $this->getRepository()->findAll(),
        ];
    }

    /**
     * @Route("/create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $entity = $this->getNewObject();

        $form = $this->createForm($this->getNewFormType(), $entity);
        $form->add('save', SubmitType::class, [
            'label' => 'ui.form.labels.save'
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->getRedirectUrl($entity));
        }

        return [
            'form' => $form->createView(),
            'entity' => $entity,
        ];
    }

    /**
     * @Route("/{id}/edit")
     * @Template()
     */
    public function editAction(Request $request, Category $category)
    {
        $form = $this->createForm($this->getNewFormType(), $category);
        $form->add('save', SubmitType::class, [
            'label' => 'ui.form.labels.save'
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->getRedirectUrl($category));
        }

        return [
            'form' => $form->createView(),
            'entity' => $category,
        ];
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
            $errorMsg = $translator->trans('error.category.not_empty', ['%name%' => $category->getName()], 'tuna_admin');
            $this->get('session')->getFlashBag()->add('error', $errorMsg);
        }

        return $this->redirect($this->getRedirectUrl($category));
    }
}
