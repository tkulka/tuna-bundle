<?php

namespace Serializer\TheCodeine\ImageBundle\Serializer\Handler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use TheCodeine\ImageBundle\Model\ImageManagerInterface;

class ImageHandlerSpec extends ObjectBehavior
{

    function let(ImageManagerInterface $imageManagerInterface)
    {
        $this->beConstructedWith($imageManagerInterface);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\ImageBundle\Serializer\Handler\ImageHandler');
    }
}
