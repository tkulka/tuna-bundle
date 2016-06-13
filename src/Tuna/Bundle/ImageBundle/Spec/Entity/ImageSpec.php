<?php

namespace Entity\TheCodeine\ImageBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ImageSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\ImageBundle\Entity\Image');
    }
}
