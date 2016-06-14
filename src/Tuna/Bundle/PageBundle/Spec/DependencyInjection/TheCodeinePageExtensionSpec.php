<?php

namespace DependencyInjection\TheCodeine\PageBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TheCodeinePageExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\PageBundle\DependencyInjection\TheCodeinePageExtension');
    }
}
