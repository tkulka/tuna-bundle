<?php

namespace Command\TheCodeine\AdminBundle\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FixBodyHTMLCommandSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\AdminBundle\Command\FixBodyHTMLCommand');
    }
}
