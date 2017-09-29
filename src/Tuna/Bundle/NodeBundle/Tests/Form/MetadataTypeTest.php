<?php

namespace TunaCMS\Bundle\NodeBundle\Tests\Form;

use Symfony\Component\Form\Test\TypeTestCase;
use TunaCMS\Bundle\NodeBundle\Form\MetadataType;
use TunaCMS\Bundle\NodeBundle\Tests\Model\DummyMetadata;

class MetadataTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'title' => 'title',
            'description' => 'description',
            'keywords' => 'foo bar',
            'indexable' => true,
        ];

        $data = new DummyMetadata();
        $form = $this->factory->create(MetadataType::class, $data);

        $form->submit($formData);

        $expected = new DummyMetadata();
        $expected
            ->setTitle($formData['title'])
            ->setDescription($formData['description'])
            ->setKeywords($formData['keywords'])
            ->setIndexable($formData['indexable'])
        ;
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $form->getData());
    }
}
