<?php

namespace Entity\TheCodeine\NewsBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NewsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\NewsBundle\Entity\News');
    }
}
