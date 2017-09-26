<?php

namespace TunaCMS\AdminBundle\Controller;

use AppBundle\Entity\Menu;
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
     * @Route("/", name="tunacms_admin_menu_index")
     */
    public function indexAction(Request $request)
    {
        $repository = $this->getMenuRepository();
        $roots = $repository->getRoots();

        foreach ($roots as $root) {
            $repository->loadWholeTree($root);
        }

        return $this->render('@TunaCMSAdmin/menu/index.html.twig', [
            'menu_items' => $roots,
        ]);
    }

    /**
     * @Route("/{parent}/create/{type}", name="tunacms_admin_menu_create")
     */
    public function createAction(Request $request, $type, MenuInterface $parent)
    {
        return $this->handleMenuCreation($request, $type, $parent);
    }

    /**
     * @Route("/{parent}/create/node/{type}", name="tunacms_admin_menu_create_node")
     */
    public function createNodeAction(Request $request, $type, MenuInterface $parent)
    {
        return $this->handleMenuCreation($request, self::MENU_NODE, $parent, [
            'node_type' => $type,
        ]);
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
    public function deleteAction(Request $request, MenuInterface $node)
    {
        $this->getDoctrine()->getManager()->remove($node);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->getRedirectUrl($request, $node));
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

    protected function handleMenuCreation(Request $request, $menuType, MenuInterface $parent, $formOptions = [])
    {
        $menuFactory = $this->get('tuna_cms_bundle_menu.factory.menu_factory');

        $menu = $menuFactory->getInstance($menuType);
        $formType = $menuFactory->getFormClass($menuType);

        $menu->setParent($parent);

        $form = $this->createForm($formType, $menu, $formOptions);

        return $this->handleForm($form, $menu, $request);
    }

    protected function getMenuClass()
    {
        return Menu::class;
    }

    /**
     * @return MenuRepositoryInterface|EntityRepository
     */
    protected function getMenuRepository()
    {
        return $this->getDoctrine()->getRepository($this->getMenuClass());
    }

    protected function getFormType($nodeType, Request $request, MenuInterface $node = null)
    {
        if (!$nodeType) {
            $nodeType = $this->getNodeType($request, $node);
        }

        return $this->get('tuna_cms_node.node_manager')->getFormType($nodeType);
    }

    protected function getNewInstance($nodeType, Request $request, MenuInterface $node = null)
    {
        if (!$nodeType) {
            $nodeType = $this->getNodeType($request, $node);
        }

        return $this->get('tuna_cms_node.node_manager')->getNewInstance($nodeType);
    }

    protected function getNodeType(Request $request, MenuInterface $node = null)
    {
        if ($request->query->has('node-type')) {
            // get node type from query parameter
            return $request->query->get('node-type');
        }

        if ($node) {
            // nodeManager methods can work with string type, or MenuInterface objects
            return $node;
        }

        return null;
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

        $template = $this->get('tuna_cms_bundle_menu.factory.menu_factory')->getTemplate($menu, 'edit');

        return $this->render($template, [
            'node' => $menu,
            'form' => $form->createView(),
        ]);
    }

    protected function getRedirectUrl(Request $request, MenuInterface $node = null)
    {
        return $this->generateUrl('tunacms_admin_menu_index');
    }

    protected function getTemplate($name, Request $request, MenuInterface $node = null)
    {
        return $this->get('tuna_cms_bundle_node.factory.node_factory')->getTemplate($name, $this->getNodeType($request, $node));
    }
}
