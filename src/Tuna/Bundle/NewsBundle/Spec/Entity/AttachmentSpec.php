<?php

namespace Entity\TheCodeine\NewsBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AttachmentSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\FileBundle\Entity\Attachment');
    }
}
