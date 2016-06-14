<?php

namespace DependencyInjection\TheCodeine\TagBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigurationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\TagBundle\DependencyInjection\Configuration');
    }
}
