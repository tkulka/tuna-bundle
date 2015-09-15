<?php

namespace TheCodeine\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use TheCodeine\NewsBundle\Entity\Category;
use TheCodeine\NewsBundle\Entity\Categories;

use  TheCodeine\NewsBundle\Form\CategoryType;
use TheCodeine\NewsBundle\Form\CategoryEditType;

class CategoryController extends Controller
{

    /**
     *
     * @Template()
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');

        $repository = $em->getRepository('TheCodeine\NewsBundle\Entity\Category');

        $mainCategories = $repository->createQueryBuilder('c')
            ->where('c.parent IS NULL')
            ->getQuery()
            ->getResult();

        $categoriesArray = array();

        foreach ($mainCategories as $category) {
            $categoriesArray = array_merge($categoriesArray, $repository->children($category));
        }

        $categories = new Categories();
        $categories->setRows($categoriesArray);

        $form = $this->createForm(new CategoryEditType(), $categories);
        $form->handleRequest($request);
        if ($form->isValid()) {
            foreach ($categoriesArray as $category) {
                $em->persist($category);
            }
            $em->flush();
            $em->clear();

            return $this->redirect($this->generateUrl('thecodeine_news_list'));
        }

        return array(
            'form'      => $form->createView()
        );
    }
}