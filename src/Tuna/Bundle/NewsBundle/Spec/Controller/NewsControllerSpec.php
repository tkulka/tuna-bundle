<?php

namespace Controller\TheCodeine\NewsBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NewsControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\NewsBundle\Controller\NewsController');
    }
}
