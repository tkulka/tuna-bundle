<?php

namespace Entity\TheCodeine\NewsBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NewsCategorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\NewsBundle\Entity\NewsCategory');
    }
}
