<?php

namespace DependencyInjection\TheCodeine\VideoBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigurationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\VideoBundle\DependencyInjection\Configuration');
    }
}
