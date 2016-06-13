<?php

namespace Imagine\TheCodeine\ImageBundle\Imagine\Filter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Imagine\Image\ImageInterface;

class ApplyShapeSpec extends ObjectBehavior
{

    function let(ImageInterface $imageInterface)
    {
        $this->beConstructedWith($imageInterface);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\ImageBundle\Imagine\Filter\ApplyShape');
    }
}
