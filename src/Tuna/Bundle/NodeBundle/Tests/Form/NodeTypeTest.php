<?php

namespace TunaCMS\Bundle\NodeBundle\Tests\Form;

use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use TunaCMS\Bundle\NodeBundle\Form\NodeType;
use TunaCMS\Bundle\NodeBundle\Tests\Model\DummyMetadata;
use TunaCMS\Bundle\NodeBundle\Tests\Model\DummyNode;

class NodeTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        return [
            new PreloadedExtension(
                [
                    new NodeType(DummyMetadata::class),
                ], []
            ),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'metadata' => [
                'title' => 'title',
                'description' => 'description',
                'keywords' => 'foo bar',
                'indexable' => true,
            ],
        ];

        $data = new DummyNode();
        $form = $this->factory->create(NodeType::class, $data);

        $form->submit($formData);

        $expected = new DummyNode();
        $expected->setMetadata(new DummyMetadata());
        $expected->getMetadata()
            ->setTitle($formData['metadata']['title'])
            ->setDescription($formData['metadata']['description'])
            ->setKeywords($formData['metadata']['keywords'])
            ->setIndexable($formData['metadata']['indexable'])
        ;
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $form->getData());
    }
}
