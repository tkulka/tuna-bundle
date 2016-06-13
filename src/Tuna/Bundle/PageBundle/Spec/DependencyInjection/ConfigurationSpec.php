<?php

namespace DependencyInjection\TheCodeine\PageBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigurationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\PageBundle\DependencyInjection\Configuration');
    }
}
