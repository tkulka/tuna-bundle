<?php

namespace DependencyInjection\TheCodeine\AdminBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TheCodeineAdminExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\AdminBundle\DependencyInjection\TheCodeineAdminExtension');
    }
}
