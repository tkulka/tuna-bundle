<?php

namespace TunaCMS\Bundle\FileBundle\Tests\Form;

use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use TunaCMS\Bundle\FileBundle\Form\UploadedFileType;

class UploadedFileTypeTest extends TypeTestCase
{
    private $validator;

    protected function getExtensions()
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->validator
            ->method('validate')
            ->will($this->returnValue(new ConstraintViolationList()))
        ;

        $this->validator
            ->method('getMetadataFor')
            ->will($this->returnValue(new ClassMetadata(Form::class)))
        ;

        return [
            new ValidatorExtension($this->validator),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'file' => [
                'path' => '/root/foo',
                'filename' => 'test.jpeg',
            ],
        ];

        $form = $this->factory->create(UploadedFileType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach ($formData as $key => $value) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
