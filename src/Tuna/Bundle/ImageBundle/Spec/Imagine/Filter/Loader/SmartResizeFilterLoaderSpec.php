<?php

namespace Imagine\TheCodeine\ImageBundle\Imagine\Filter\Loader;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Imagine\Image\ImagineInterface;

class SmartResizeFilterLoaderSpec extends ObjectBehavior
{

    function let(ImagineInterface $imagineInterface)
    {
        $this->beConstructedWith($imagineInterface);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\ImageBundle\Imagine\Filter\Loader\SmartResizeFilterLoader');
    }
}
