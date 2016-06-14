<?php

namespace DependencyInjection\TheCodeine\VideoBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TheCodeineVideoExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\VideoBundle\DependencyInjection\TheCodeineVideoExtension');
    }
}
