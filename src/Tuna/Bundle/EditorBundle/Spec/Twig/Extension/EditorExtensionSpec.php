<?php

namespace Twig\TheCodeine\EditorBundle\Twig\Extension;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EditorExtensionSpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith(true, false, '');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\EditorBundle\Twig\Extension\EditorExtension');
    }
}
