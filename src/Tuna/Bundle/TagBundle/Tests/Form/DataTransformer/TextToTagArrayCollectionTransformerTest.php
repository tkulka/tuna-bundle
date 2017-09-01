<?php

namespace TunaCMS\Bundle\TagBundle\Tests\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use TunaCMS\Bundle\TagBundle\Doctrine\TagManager;
use TunaCMS\Bundle\TagBundle\Entity\Tag;
use TunaCMS\Bundle\TagBundle\Form\DataTransformer\TextToTagArrayCollectionTransformer;

class TextToTagArrayCollectionTransformerTest extends TestCase
{
    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    private $accessor;

    /**
     * @var TagManager
     */
    private $tagManager;

    /**
     * @var TextToTagArrayCollectionTransformer
     */
    private $dataTransformer;

    protected function setUp()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();

        $this->tagManager = $this->getMockBuilder(TagManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['findTagsByName'])
            ->getMock()
        ;

        $this->dataTransformer = new TextToTagArrayCollectionTransformer($this->tagManager);
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     * @expectedExceptionMessage Expected an object of Doctrine\Common\Collections\ArrayCollection or Doctrine\ORM\PersistentCollection type.
     */
    public function testTransformWrongValue()
    {
        $this->dataTransformer->transform(new \StdClass());
    }

    /**
     * @dataProvider getTransformData
     *
     * @param array $data
     */
    public function testTransform(array $data)
    {
        $object = new ArrayCollection();
        foreach ($data as $value) {
            $tag = new Tag();
            $tag->setName($value);
            $object->add($tag);
        }

        $this->assertEquals(implode(',', $data), $this->dataTransformer->transform($object));
    }

    public function getTransformData()
    {
        return [
            [
                [],
            ],
            [
                ['τάχιστη'],
            ],
            [
                ['foo', 'bar', 'test'],
            ],
        ];
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     * @expectedExceptionMessage Expected a string.
     */
    public function testReverseTransformWrongValue()
    {
        $this->dataTransformer->reverseTransform(['foo']);
    }

    public function testReverseTransformWhenValueIsEmpty()
    {
        $object = new ArrayCollection();

        $this->tagManager
            ->expects($this->never())
            ->method('findTagsByName')
        ;

        $this->assertEquals($object, $this->dataTransformer->reverseTransform(''));
    }

    /**
     * @dataProvider getReverseTransformData
     *
     * @param string $data
     * @param array $expected
     */
    public function testReverseTransformWhenTagsNotExist($data, array $expected)
    {
        $object = new ArrayCollection();
        foreach ($expected as $value) {
            $tag = new Tag();
            $tag->setName($value);
            $object->add($tag);
        }

        $this->tagManager
            ->expects($this->once())
            ->method('findTagsByName')
            ->will($this->returnValue([]))
        ;

        $this->assertEquals($object, $this->dataTransformer->reverseTransform($data));
    }

    public function getReverseTransformData()
    {
        return [
            [
                'τάχιστη',
                ['τάχιστη'],
            ],
            [
                'foo,bar,test',
                ['foo', 'bar', 'test'],
            ],
        ];
    }

    public function testReverseTransformWhenTagExists()
    {
        $data = 'foo,bar,test,exists';
        $object = new ArrayCollection();

        $tagExists = new Tag();
        $tagExists->setName('exists');

        $object->add($tagExists);

        $reflectionClass = new \ReflectionClass(Tag::class);
        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($tagExists, 3456);

        foreach (['foo', 'bar', 'test'] as $value) {
            $tag = new Tag();
            $tag->setName($value);
            $object->add($tag);
        }

        $this->tagManager
            ->expects($this->once())
            ->method('findTagsByName')
            ->will($this->returnValue([
                $tagExists,
            ]))
        ;

        $this->assertEquals($object, $this->dataTransformer->reverseTransform($data));
    }
}
