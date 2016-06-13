<?php

namespace DependencyInjection\TheCodeine\NewsBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ImappPageExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\NewsBundle\DependencyInjection\ImappPageExtension');
    }
}
