<?php

namespace TunaCMS\AdminBundle\Twig;

use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;
use TunaCMS\Bundle\NodeBundle\NodeManager;

class NodeExtension extends \Twig_Extension
{
    /**
     * @var NodeManager
     */
    protected $nodeManager;

    public function __construct(NodeManager $nodeManager)
    {
        $this->nodeManager = $nodeManager;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('resolve_node_template', [$this, 'resolveNodeTemplate']),
            new \Twig_SimpleFunction('resolve_node_type', [$this, 'resolveNodeType']),
        ];
    }

    public function resolveNodeTemplate(MenuInterface $node = null, $template = 'node_item')
    {
        return $this->nodeManager->getTemplate($template, $node);
    }

    public function resolveNodeType(MenuInterface $node = null)
    {
        return $this->nodeManager->resolveType($node);
    }
}
