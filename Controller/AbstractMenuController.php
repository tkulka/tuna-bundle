<?php

namespace TunaCMS\AdminBundle\Controller;

use AppBundle\Entity\MenuNode;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;
use TunaCMS\Bundle\MenuBundle\Repository\MenuRepositoryInterface;

class AbstractMenuController extends Controller
{
    const MENU_NODE = 'menu_node';

    /**
     * @Route("/{parent}/create/{type}", name="tunacms_admin_menu_create")
     * @Route("/{parent}/create/node/{nodeType}", name="tunacms_admin_menu_create_node")
     */
    public function createAction(Request $request, MenuInterface $parent, $type = self::MENU_NODE, $nodeType = null)
    {
        $menuFactory = $this->get('tuna_cms_bundle_menu.factory.menu_factory');

        $menu = $menuFactory->getInstance($type);
        $menu->setParent($parent);

        $formType = $menuFactory->getFormClass($menu);
        $form = $this->createForm($formType, $menu, [
            'node_type' => $nodeType,
        ]);

        return $this->handleForm($form, $menu, $request);
    }

    /**
     * @Route("/{id}/edit", name="tunacms_admin_menu_edit")
     */
    public function editAction(Request $request, MenuInterface $menu)
    {
        $menuFactory = $this->get('tuna_cms_bundle_menu.factory.menu_factory');

        $formType = $menuFactory->getFormClass($menu);
        $form = $this->createForm($formType, $menu);

        return $this->handleForm($form, $menu, $request);
    }

    /**
     * @Route("/{id}/delete", name="tunacms_admin_menu_delete")
     */
    public function deleteAction(Request $request, MenuInterface $menu)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($menu);
        $em->flush();

        return $this->redirect($this->getRedirectUrl($request, $menu));
    }

    /**
     * @Route("/save-order", name="tunacms_admin_menu_saveorder")
     * @Method("POST")
     */
    public function saveOrderAction(Request $request)
    {
        $entities = $this->getMenuRepository()->findAll();
        $order = $request->request->get('order', []);

        $nodes = [];
        /* @var $entity MenuInterface */
        foreach ($entities as $entity) {
            $nodes[$entity->getId()] = $entity;
        }

        foreach ($order as $nodeTreeData) {
            $nodes[(int)$nodeTreeData['id']]->setTreeData(
                (int)$nodeTreeData['left'],
                (int)$nodeTreeData['right'],
                (int)$nodeTreeData['depth'],
                isset($nodes[$nodeTreeData['parent_id']]) ? $nodes[$nodeTreeData['parent_id']] : null
            );
        }

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse('ok');
    }

    /**
     * @return MenuRepositoryInterface|EntityRepository
     */
    protected function getMenuRepository()
    {
        $menuModel = $this->get('tuna_cms_bundle_menu.factory.menu_factory')->getModel();

        return $this->getDoctrine()->getRepository($menuModel);
    }

    protected function handleForm(Form $form, MenuInterface $menu, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($menu);
            $em->flush();

            return $this->redirect($this->getRedirectUrl($request, $menu));
        }

        $template = $this->getTemplate($menu, 'edit');

        return $this->render($template, [
            'node' => $menu,
            'form' => $form->createView(),
        ]);
    }

    protected function getRedirectUrl(Request $request, MenuInterface $menu = null)
    {
        return $this->generateUrl('tunacms_dashboard');
    }

    protected function getTemplate(MenuInterface $menu, $name)
    {
        if ($menu instanceof MenuNode) {
            return $this->get('tuna_cms_bundle_node.factory.node_factory')->getTemplate($menu->getNode(), $name);
        }

        return $this->get('tuna_cms_bundle_menu.factory.menu_factory')->getTemplate($menu, $name);
    }
}
