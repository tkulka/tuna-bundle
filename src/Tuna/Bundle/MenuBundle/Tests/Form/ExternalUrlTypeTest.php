<?php

namespace TunaCMS\Bundle\MenuBundle\Tests\Form;

use Symfony\Component\Form\Test\TypeTestCase;
use TunaCMS\Bundle\MenuBundle\Form\ExternalUrlType;
use TunaCMS\Bundle\MenuBundle\Tests\Model\DummyExternalUrl;

class ExternalUrlTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'url' => 'google.com',
        ];

        $data = new DummyExternalUrl();
        $form = $this->factory->create(ExternalUrlType::class, $data);

        $form->submit($formData);

        $expected = new DummyExternalUrl();
        $expected
            ->setUrl($formData['url'])
            ->setDisplayingChildren(false)
            ->setPublished(false)
        ;
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $form->getData());
    }
}
