<?php

namespace EventListener\TheCodeine\PageBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DefaultTranslationListenerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\PageBundle\EventListener\DefaultTranslationListener');
    }
}
