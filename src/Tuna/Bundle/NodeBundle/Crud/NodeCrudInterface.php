<?php

namespace TunaCMS\Bundle\NodeBundle\Crud;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;

interface NodeCrudInterface
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request);

    /**
     * @param string $nodeType
     * @param Request $request
     *
     * @return Response
     */
    public function createAction($nodeType, Request $request);

    /**
     * @param Request $request
     * @param NodeInterface $node
     *
     * @return Response
     */
    public function editAction(Request $request, NodeInterface $node);

    /**
     * @param Request $request
     * @param NodeInterface $node
     *
     * @return Response
     */
    public function deleteAction(Request $request, NodeInterface $node);

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function saveOrderAction(Request $request);
}
