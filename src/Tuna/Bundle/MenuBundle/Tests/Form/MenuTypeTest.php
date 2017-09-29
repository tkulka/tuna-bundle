<?php

namespace TunaCMS\Bundle\MenuBundle\Tests\Form;

use Symfony\Component\Form\Test\TypeTestCase;
use TunaCMS\Bundle\MenuBundle\Form\MenuType;
use TunaCMS\Bundle\MenuBundle\Tests\Model\DummyMenu;

class MenuTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'name' => 'menu_name',
            'label' => 'menu_label',
            'displayingChildren' => true,
            'published' => false,
        ];

        $data = new DummyMenu();
        $form = $this->factory->create(MenuType::class, $data);

        $form->submit($formData);

        $expected = new DummyMenu();
        $expected
            ->setName($formData['name'])
            ->setLabel($formData['label'])
            ->setDisplayingChildren($formData['displayingChildren'])
            ->setPublished($formData['published'])
        ;
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $form->getData());
    }
}
