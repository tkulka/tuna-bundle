<?php

namespace TheCodeine\TagBundle\Test\Form\DataTransformer;

use TheCodeine\TagBundle\Entity\Tag;
use TheCodeine\TagBundle\Form\DataTransformer\TextToTagArrayCollectionTransformer;

use Doctrine\Common\Collections\ArrayCollection;

class TextToTagArrayCollectionTransformerTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $transformer = new TextToTagArrayCollectionTransformer($this->getTagManager());

        $t1 = new Tag();
        $t1->setName('tag1');

        $t2 = new Tag();
        $t2->setName('tag2');

        $t3 = new Tag();
        $t3->setName('tag3');

        $tags = new ArrayCollection();
        $tags->add($t1);
        $tags->add($t2);
        $tags->add($t3);

        $this->assertEquals('tag1,tag2,tag3', $transformer->transform($tags));
    }

    public function testReverseTransform()
    {
        $tagManager = $this->getTagManager();
        $transformer = new TextToTagArrayCollectionTransformer($tagManager);

        $t1 = new Tag();
        $t1->setName('tag1');

        $t2 = new Tag();
        $t2->setName('tag2');

        $tagManager->expects($this->once())->method('findTagsByName')->will($this->returnValue(array($t1, $t2)));
        $tagManager->expects($this->once())->method('createTag')->will($this->returnValue(new Tag()));

        $tags = $transformer->reverseTransform('tag1,tag2,tag3');

        $this->assertCount(3, $tags);
    }

    private function getTagManager()
    {
        return $this->getMock('TheCodeine\TagBundle\Model\TagManagerInterface');
    }
}