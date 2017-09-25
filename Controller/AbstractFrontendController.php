<?php

namespace TunaCMS\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;
use TunaCMS\Bundle\NodeBundle\Model\MenuNodeInterface;
use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;

class AbstractFrontendController extends Controller
{
    /**
     * @Route("/{slug}", requirements={"slug"=".*"}, name="tuna_menu_item")
     */
    public function pageAction(Request $request, MenuInterface $menu)
    {
        if (
            !$menu instanceof MenuNodeInterface
            || !$menu->isPublished()
            || !$menu->getNode()
        ) {
            throw new NotFoundHttpException();
        }

        $node = $menu->getNode();

        return $this->renderNode($node, $request);
    }

    private function renderNode(NodeInterface $node, Request $request)
    {
        if ($node->getControllerAction()) {
            return $this->forward($node->getControllerAction(), $request->attributes->all());
        }

        return $this->render($this->getTemplate($node), [
            'node' => $node,
        ]);
    }

    private function getTemplate(NodeInterface $node)
    {
        if ($node->getActionTemplate()) {
            return $node->getActionTemplate();
        }

        $templatePath = sprintf('frontend/nodes/%s.html.twig', $node->getSlug() ?: 'home');

        if ($this->get('templating')->exists($templatePath)) {
            return $templatePath;
        }

        return 'frontend/default_page.html.twig';
    }
}
