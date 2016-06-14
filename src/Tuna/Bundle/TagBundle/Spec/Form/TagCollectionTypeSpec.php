<?php

namespace Form\TheCodeine\TagBundle\Form;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheCodeine\TagBundle\Model\TagManagerInterface;


class TagCollectionTypeSpec extends ObjectBehavior
{
    function it_is_initializable(TagManagerInterface $tagManager)
    {
        $this->beConstructedWith($tagManager);
        $this->shouldHaveType('TheCodeine\TagBundle\Form\TagCollectionType');
    }
}
