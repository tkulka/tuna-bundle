<?php

namespace TunaCMS\Bundle\FileBundle\Tests\Form;

use Symfony\Component\Form\Test\TypeTestCase;
use TunaCMS\Bundle\FileBundle\Entity\Image;
use TunaCMS\Bundle\FileBundle\Form\ImageType;

class ImageTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'path' => '/root/foo',
            'filename' => 'test.jpeg',
        ];

        $form = $this->factory->create(ImageType::class);

        $object = new Image();
        $object
            ->setPath($formData['path'])
            ->setFilename($formData['filename']);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach ($formData as $key => $value) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
