<?php

namespace TunaCMS\Bundle\MenuBundle\Tests\Twig;

use PHPUnit\Framework\TestCase;
use TunaCMS\Bundle\NodeBundle\Factory\NodeFactory;
use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;
use TunaCMS\Bundle\NodeBundle\Twig\NodeExtension;

class NodeExtensionTest extends TestCase
{
    public function testResolveNodeType()
    {
        $nodeFactory = $this->createMock(NodeFactory::class);
        $extension = new NodeExtension($nodeFactory);
        $node = $this->createMock(NodeInterface::class);

        $nodeFactory
            ->expects($this->once())
            ->method('getTypeName')
            ->with($this->equalTo($node))
        ;

        $extension->resolveNodeType($node);
    }
}
