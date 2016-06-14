<?php

namespace Entity\TheCodeine\TagBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TagSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\TagBundle\Entity\Tag');
    }
}
