<?php

namespace Controller\TheCodeine\PageBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PageControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\PageBundle\Controller\PageController');
    }
}
