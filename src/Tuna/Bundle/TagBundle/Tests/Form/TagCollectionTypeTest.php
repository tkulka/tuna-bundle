<?php

namespace TheCodeine\TagBundle\Test\Form;

use TheCodeine\TagBundle\Entity\Tag;
use TheCodeine\TagBundle\Form\TagCollectionType;

use Doctrine\Common\Collections\ArrayCollection;

class TagCollectionTypeTest extends \Symfony\Component\Form\Test\TypeTestCase
{
    public function testTagCollection()
    {
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

        $form = $this->factory->create(new TagCollectionType($this->getTagManager()));
        $form->setData($tags);
        $view = $form->createView();

        $this->assertSame('tag1,tag2,tag3', $view->vars['value']);
    }

    public function testSubmitTagCollection()
    {
        $tagManager = $this->getTagManager();

        $t1 = new Tag();
        $t1->setName('tag1');

        $t2 = new Tag();
        $t2->setName('tag3');

        $tagManager->expects($this->once())->method('findTagsByName')->will($this->returnValue(array($t1, $t2)));
        $tagManager->expects($this->once())->method('createTag')->will($this->returnValue(new Tag()));

        $form = $this->factory->create(new TagCollectionType($tagManager));
        $form->submit('tag1,tag2,tag3');
        $data = $form->getData();

        $this->assertCount(3, $data);
    }

    private function getTagManager()
    {
        return $this->getMock('TheCodeine\TagBundle\Model\TagManagerInterface');
    }
}