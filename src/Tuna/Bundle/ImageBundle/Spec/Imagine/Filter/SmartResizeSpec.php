<?php

namespace Imagine\TheCodeine\ImageBundle\Imagine\Filter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Imagine\Image\BoxInterface;

class SmartResizeSpec extends ObjectBehavior
{

    function let(BoxInterface $boxInterface)
    {
        $this->beConstructedWith($boxInterface);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\ImageBundle\Imagine\Filter\SmartResize');
    }
}
