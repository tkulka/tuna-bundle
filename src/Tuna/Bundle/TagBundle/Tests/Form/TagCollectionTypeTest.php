<?php

namespace TunaCMS\Bundle\TagBundle\Tests\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use TunaCMS\Bundle\TagBundle\Doctrine\TagManager;
use TunaCMS\Bundle\TagBundle\Entity\Tag;
use TunaCMS\Bundle\TagBundle\Form\TagCollectionType;

class TagCollectionTypeTest extends TypeTestCase
{
    /**
     * @var TagManager
     */
    private $tagManager;

    protected function setUp()
    {
        $this->tagManager = $this->getMockBuilder(TagManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['findTagsByName'])
            ->getMock();

        $this->tagManager
            ->method('findTagsByName')
            ->will($this->returnValue([]));

        parent::setUp();
    }

    protected function getExtensions()
    {
        $tagCollectionType = new TagCollectionType($this->tagManager);

        return [
            new PreloadedExtension(
                [
                    $tagCollectionType,
                ], []
            ),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = 'foo,bar,test';

        $form = $this->factory->create(TagCollectionType::class);

        $object = new ArrayCollection();
        foreach (explode(',', $formData) as $value) {
            $tag = new Tag();
            $tag->setName($value);
            $object->add($tag);
        }

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());
    }
}
