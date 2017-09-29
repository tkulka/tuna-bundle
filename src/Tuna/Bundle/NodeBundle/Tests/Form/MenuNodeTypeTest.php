<?php

namespace TunaCMS\Bundle\NodeBundle\Tests\Form;

use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use TunaCMS\Bundle\NodeBundle\Factory\NodeFactory;
use TunaCMS\Bundle\NodeBundle\Form\MenuNodeType;
use TunaCMS\Bundle\NodeBundle\Form\NodeType;
use TunaCMS\Bundle\NodeBundle\Tests\Model\DummyMenuNode;
use TunaCMS\Bundle\NodeBundle\Tests\Model\DummyMetadata;
use TunaCMS\Bundle\NodeBundle\Tests\Model\DummyNode;

class MenuNodeTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $nodeFactory = $this->createMock(NodeFactory::class);

        $nodeFactory
            ->expects($this->any())
            ->method('getFormClass')
            ->will($this->returnValue(NodeType::class))
        ;

        $nodeFactory
            ->expects($this->any())
            ->method('getInstance')
            ->will($this->returnValue(new DummyNode()))
        ;

        return [
            new PreloadedExtension(
                [
                    new MenuNodeType($nodeFactory),
                    new NodeType(DummyMetadata::class),
                ], []
            ),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'node' => [
                'metadata' => [
                    'title' => 'title',
                    'description' => 'description',
                    'keywords' => 'foo bar',
                    'indexable' => true,
                ],
            ],
        ];

        $data = new DummyMenuNode();
        $data->setNode(new DummyNode());
        $form = $this->factory->create(MenuNodeType::class, $data);

        $form->submit($formData);

        $expected = new DummyMenuNode();
        $expected
            ->setNode(new DummyNode())
            ->setPublished(false)
            ->setDisplayingChildren(false)
        ;
        $expected
            ->getNode()
            ->setMetadata(new DummyMetadata())
            ->getMetadata()
            ->setTitle($formData['node']['metadata']['title'])
            ->setDescription($formData['node']['metadata']['description'])
            ->setKeywords($formData['node']['metadata']['keywords'])
            ->setIndexable($formData['node']['metadata']['indexable'])
        ;
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $form->getData());
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\InvalidArgumentException
     * @expectedExceptionMessage You have to either provide $menu with existing `node` or `node_type` option.
     */
    public function testSubmitDataWhenMenuNodeIsEmptyAndWithoutNodeTypeOption()
    {
        $data = new DummyMenuNode();
        $form = $this->factory->create(MenuNodeType::class, $data);
        $form->submit([]);
    }

    public function testSubmitDataWhenMenuNodeIsEmpty()
    {
        $data = new DummyMenuNode();
        $form = $this->factory->create(MenuNodeType::class, $data, [
            'node_type' => 'node'
        ]);
        $form->submit([]);

        $this->assertInstanceOf(DummyNode::class, $data->getNode());
    }
}
