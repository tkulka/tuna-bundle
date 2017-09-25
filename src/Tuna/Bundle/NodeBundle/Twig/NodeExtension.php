<?php

namespace TunaCMS\Bundle\NodeBundle\Twig;

use TunaCMS\Bundle\NodeBundle\Factory\NodeFactory;
use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;

class NodeExtension extends \Twig_Extension
{
    /**
     * @var NodeFactory
     */
    protected $nodeFactory;

    public function __construct(NodeFactory $nodeFactory)
    {
        $this->nodeFactory = $nodeFactory;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('resolve_node_type', [$this, 'resolveNodeType']),
        ];
    }

    public function resolveNodeType(NodeInterface $node)
    {
        return $this->nodeFactory->getTypeName($node);
    }
}
