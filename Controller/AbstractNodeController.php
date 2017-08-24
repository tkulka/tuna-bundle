<?php

namespace TunaCMS\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TunaCMS\Bundle\NodeBundle\Crud\NodeCrudInterface;
use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;
use TunaCMS\Bundle\NodeBundle\NodeManager;

/**
 * @Route("/node")
 */
class AbstractNodeController extends Controller
{
    /**
     * @Route("/", name="tunacms_admin_node_index")
     */
    public function indexAction(Request $request)
    {
        return $this->getCrudService($request)->indexAction($request);
    }

    /**
     * @Route("/create/{type}", name="tunacms_admin_node_create")
     */
    public function createAction(Request $request, $type = NodeManager::BASE_TYPE)
    {
        return $this->getCrudService($request)->createAction($type, $request);
    }

    /**
     * @Route("/{id}/edit", name="tunacms_admin_node_edit")
     */
    public function editAction(Request $request, NodeInterface $node)
    {
        return $this->getCrudService($request, $node)->editAction($request, $node);
    }

    /**
     * @Route("/{id}/delete", name="tunacms_admin_node_delete")
     */
    public function deleteAction(Request $request, NodeInterface $node)
    {
        return $this->getCrudService($request, $node)->deleteAction($request, $node);
    }

    /**
     * @Route("/save-order", name="tunacms_admin_node_saveorder")
     * @Method("POST")
     */
    public function saveOrderAction(Request $request)
    {
        return $this->getCrudService($request)->saveOrderAction($request);
    }

    /**
     * @param Request $request
     * @param NodeInterface|null $node
     *
     * @return NodeCrudInterface
     */
    protected function getCrudService(Request $request, NodeInterface $node = null)
    {
        return $this->get('tuna_cms_node.crud.default');
    }
}
