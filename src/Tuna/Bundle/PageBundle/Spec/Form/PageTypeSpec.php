<?php

namespace Form\TheCodeine\PageBundle\Form;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PageTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\PageBundle\Form\PageType');
    }
}
