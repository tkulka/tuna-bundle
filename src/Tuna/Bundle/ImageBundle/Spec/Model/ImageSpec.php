<?php

namespace Model\TheCodeine\ImageBundle\Model;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ImageSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\ImageBundle\Model\Image');
    }
}
