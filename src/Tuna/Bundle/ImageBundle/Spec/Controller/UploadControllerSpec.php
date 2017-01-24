<?php

namespace Controller\TheCodeine\ImageBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UploadControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\ImageBundle\Controller\UploadController');
    }
}
