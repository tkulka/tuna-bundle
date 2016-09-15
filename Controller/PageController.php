<?php

namespace TheCodeine\AdminBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use TheCodeine\MenuBundle\Entity\Menu;
use TheCodeine\PageBundle\Entity\AbstractPage;
use TheCodeine\PageBundle\Entity\Page;

/**
 * @Route("/page")
 */
class PageController extends \TheCodeine\PageBundle\Controller\PageController
{
    public function getRedirectUrl(AbstractPage $page = null, Request $request = null)
    {
        if ($request && $request->query->get('redirect') == 'dashboard') {
            return $this->generateUrl('tuna_admin_dashboard');
        }
        return parent::getRedirectUrl($page);
    }

    /**
     *
     * @Route("/list", name="tuna_page_list")
     * @Template()
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('TheCodeinePageBundle:Page')->getListQuery();
        $menuMap = $em->getRepository('TheCodeineMenuBundle:Menu')->getPageMap();
        $page = $request->get('page', 1);
        $limit = 10;

        return array(
            'pagination' => $this->get('knp_paginator')->paginate($query, $page, $limit),
            'offset' => ($page - 1) * $limit,
            'menuMap' => $menuMap,
        );
    }

    /**
     * @Route("/create", name="tuna_page_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted('create', 'pages');

        $page = $this->getNewPage();
        $form = $this->createForm($this->getNewFormType($page, !$request->isXmlHttpRequest()), $page);
        $form->add('save', 'submit');
        if ($request->query->get('menu') == 'add') {
            if (($parentId = $request->query->get('menuParentId'))) {
                $parent = $this->getDoctrine()->getManager()->getReference('TheCodeineMenuBundle:Menu', $parentId);
            }
            $form->add('menuParent', EntityType::class, array(
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('p')
                            ->orderBy('p.root', 'ASC')
                            ->addOrderBy('p.lft', 'ASC');
                    },
                    'class' => Menu::class,
                    'property' => 'indentedName',
                    'required' => true,
                    'mapped' => false,
                    'data' => isset($parent) ? $parent : null,
                )
            );
        }

        $return = $this->handleCreateForm($request, $form, $page);
        $this->handleMenuParent($request, $form, $page);

        return $return;
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
        $this->denyAccessUnlessGranted('delete', 'pages');

        return parent::deleteAction($request, $id);
    }

    /**
     * @Route("/add-to-menu", name="tuna_page_add_to_menu")
     */
    public function createMenuItemAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->find('TheCodeinePageBundle:Page', $request->request->get('pageId'));
        $menuParent = $em->getReference('TheCodeineMenuBundle:Menu', $request->request->get('menuParentId'));

        $menu = new Menu('tmp');
        $menu->setPage($page);
        $menu->setParent($menuParent);

        $em->persist($menu);
        $em->flush();

        return new JsonResponse('ok');
    }

    /**
     * @param Request $request
     * @param FormInterface $form
     * @param Page $page
     */
    private function handleMenuParent(Request $request, FormInterface $form, Page $page)
    {
        if (
            $form->isValid()
            && !$request->isXmlHttpRequest()
            && $form->has('menuParent')
        ) {
            $menuParent = $form->get('menuParent')->getData();
            $em = $this->getDoctrine()->getManager();
            $menu = new Menu('tmp');
            $menu->setPage($page);
            $menu->setParent($menuParent);
            $em->persist($menu);
            $em->flush();
        }
    }
}
