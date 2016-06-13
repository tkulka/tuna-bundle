<?php

namespace Entity\TheCodeine\VideoBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class VideoSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\VideoBundle\Entity\Video');
    }
}
