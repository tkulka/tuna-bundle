<?php

namespace Form\TheCodeine\EditorBundle\Form\Type;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EditorTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\EditorBundle\Form\Type\EditorType');
    }
}
