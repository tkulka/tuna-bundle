<?php

namespace EventListener\TheCodeine\ImageBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use TheCodeine\ImageBundle\Model\ImageManagerInterface;

class UploadListenerSpec extends ObjectBehavior
{

    function let(ImageManagerInterface $imageManagerInterface)
    {
        $this->beConstructedWith($imageManagerInterface);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\ImageBundle\EventListener\UploadListener');
    }
}
