<?php

namespace TunaCMS\Bundle\NodeBundle\Crud;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;
use TunaCMS\Bundle\NodeBundle\NodeManager;

abstract class AbstractNodeCrud implements NodeCrudInterface
{
    /**
     * @var TwigEngine
     */
    protected $twig;

    /**
     * @var NodeManager
     */
    protected $nodeManager;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var RouterInterface
     */
    protected $router;

    protected $nodesConfig;

    /**
     * AbstractNodeCrud constructor.
     *
     * @param TwigEngine $twig
     * @param NodeManager $nodeManager
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $em
     * @param RouterInterface $router
     * @param $nodesConfig
     */
    public function __construct(TwigEngine $twig, NodeManager $nodeManager, FormFactoryInterface $formFactory, EntityManagerInterface $em, RouterInterface $router, $nodesConfig)
    {
        $this->twig = $twig;
        $this->nodeManager = $nodeManager;
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->router = $router;
        $this->nodesConfig = $nodesConfig;
    }

    public function indexAction(Request $request)
    {
        $nodeManager = $this->nodeManager;

        /* @var $repository \TunaCMS\Bundle\NodeBundle\Repository\MenuRepositoryInterface */
        $repository = $this->em->getRepository($nodeManager->getModel());
        $roots = $repository->getMenuRoots();

        foreach ($roots as $root) {
            $repository->loadWholeNodeTree($root);
        }

        return $this->render($this->getTemplate('index', $request), [
            'nodes' => $roots,
        ]);
    }

    public function createAction($nodeType = null, Request $request)
    {
        $node = $this->getNewInstance($nodeType, $request);

        if ($parentId = $request->query->get('parentId')) {
            $node->setParent($this->em->getReference($this->nodeManager->getModel(), $parentId));
        }

        $form = $this->createForm($this->getFormType($nodeType, $request), $node);

        return $this->handleForm($form, $node, $request);
    }

    public function editAction(Request $request, NodeInterface $node)
    {
        $form = $this->createForm($this->getFormType(null, $request, $node), $node);

        return $this->handleForm($form, $node, $request);
    }

    public function deleteAction(Request $request, NodeInterface $node)
    {
        $this->em->remove($node);
        $this->em->flush();

        return $this->redirect($this->getRedirectUrl($request, $node));
    }

    public function saveOrderAction(Request $request)
    {
        $nodeManager = $this->nodeManager;
        /* @var $entities NodeInterface[] */
        $entities = $this->em->getRepository($nodeManager->getModel())->findAll();
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

        $this->em->flush();

        return new JsonResponse('ok');
    }

    protected function getFormType($nodeType, Request $request, NodeInterface $node = null)
    {
        if (!$nodeType) {
            $nodeType = $this->getNodeType($request, $node);
        }

        return $this->nodeManager->getFormType($nodeType);
    }

    protected function getNewInstance($nodeType, Request $request, NodeInterface $node = null)
    {
        if (!$nodeType) {
            $nodeType = $this->getNodeType($request, $node);
        }

        return $this->nodeManager->getNewInstance($nodeType);
    }

    protected function getNodeType(Request $request, NodeInterface $node = null)
    {
        if ($request->query->has('node-type')) {
            // get node type from query parameter
            return $request->query->get('node-type');
        }

        if ($node) {
            // nodeManager methods can work with string type, or NodeInterface objects
            return $node;
        }

        return null;
    }

    protected function handleForm(Form $form, NodeInterface $node, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($node);
            $this->em->flush();

            return $this->redirect($this->getRedirectUrl($request, $node));
        }

        return $this->render($this->getTemplate('edit', $request, $node), [
            'node' => $node,
            'form' => $form->createView(),
        ]);
    }

    protected function getRedirectUrl(Request $request, NodeInterface $node = null)
    {
        return $this->generateUrl('tunacms_admin_node_index');
    }

    protected function getTemplate($name, Request $request, NodeInterface $node = null)
    {
        return $this->nodeManager->getTemplate($name, $this->getNodeType($request, $node));
    }

    protected function render($view, array $parameters = [], Response $response = null)
    {
        return $this->twig->renderResponse($view, $parameters, $response);
    }

    /**
     * @param string $type
     * @param null $data
     * @param array $options
     *
     * @return Form
     */
    protected function createForm($type = 'Symfony\Component\Form\Extension\Core\Type\FormType', $data = null, array $options = [])
    {
        return $this->formFactory->create($type, $data, $options);
    }

    protected function redirectToRoute($route, array $parameters = [], $status = 302)
    {
        return $this->redirect($this->generateUrl($route, $parameters), $status);
    }

    protected function generateUrl($route, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->router->generate($route, $parameters, $referenceType);
    }

    protected function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }
}
