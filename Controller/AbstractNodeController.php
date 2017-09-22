<?php

namespace TunaCMS\AdminBundle\Controller;

use AppBundle\Entity\Menu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;
use TunaCMS\Bundle\NodeBundle\NodeManager;

class AbstractNodeController extends Controller
{
    /**
     * @Route("/", name="tunacms_admin_node_index")
     */
    public function indexAction(Request $request)
    {
        /* @var $repository \TunaCMS\Bundle\MenuBundle\Repository\MenuRepositoryInterface */
        $repository = $this->getMenuRepository();
        $roots = $repository->getMenuRoots();

        foreach ($roots as $root) {
            $repository->loadWholeNodeTree($root);
        }

        return $this->render($this->getTemplate('index', $request), [
            'nodes' => $roots,
        ]);
    }

    /**
     * @Route("/create/{type}", name="tunacms_admin_node_create")
     */
    public function createAction(Request $request, $type = NodeManager::BASE_TYPE)
    {
        $node = $this->getNewInstance($type, $request);

        if ($parentId = $request->query->get('parentId')) {
            $node->setParent($this->getDoctrine()->getManager()->getReference($this->getMenuClass(), $parentId));
        }

        $form = $this->createForm($this->getFormType($type, $request), $node);

        return $this->handleForm($form, $node, $request);
    }

    /**
     * @Route("/{id}/edit", name="tunacms_admin_node_edit")
     */
    public function editAction(Request $request, MenuInterface $node)
    {
        $form = $this->createForm($this->getFormType(null, $request, $node), $node);

        return $this->handleForm($form, $node, $request);
    }

    /**
     * @Route("/{id}/delete", name="tunacms_admin_node_delete")
     */
    public function deleteAction(Request $request, MenuInterface $node)
    {
        $this->getDoctrine()->getManager()->remove($node);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->getRedirectUrl($request, $node));
    }

    /**
     * @Route("/save-order", name="tunacms_admin_node_saveorder")
     * @Method("POST")
     */
    public function saveOrderAction(Request $request)
    {
        $nodeManager = $this->get('tuna_cms_node.node_manager');

        /* @var $entities MenuInterface[] */
        $entities = $this->getMenuRepository()->findAll();
        $order = $request->request->get('order', []);

        $nodes = [];
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

    protected function getMenuClass()
    {
        return Menu::class;
    }

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

    protected function handleForm(Form $form, MenuInterface $node, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($node);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->getRedirectUrl($request, $node));
        }

        return $this->render($this->getTemplate('edit', $request, $node), [
            'node' => $node,
            'form' => $form->createView(),
        ]);
    }

    protected function getRedirectUrl(Request $request, MenuInterface $node = null)
    {
        return $this->generateUrl('tunacms_admin_node_index');
    }

    protected function getTemplate($name, Request $request, MenuInterface $node = null)
    {
        return $this->get('tuna_cms_node.node_manager')->getTemplate($name, $this->getNodeType($request, $node));
    }
}
