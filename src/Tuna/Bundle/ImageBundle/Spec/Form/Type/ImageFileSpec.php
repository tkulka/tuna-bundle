<?php

namespace Form\TheCodeine\ImageBundle\Form\Type;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ImageFileSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\ImageBundle\Form\Type\ImageFile');
    }
}
