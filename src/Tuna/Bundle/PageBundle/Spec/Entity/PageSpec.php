<?php

namespace Entity\TheCodeine\PageBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PageSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\PageBundle\Entity\Page');
    }
}
