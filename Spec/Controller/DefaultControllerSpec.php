<?php

namespace Controller\TheCodeine\AdminBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DefaultControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\AdminBundle\Controller\DefaultController');
    }
}
