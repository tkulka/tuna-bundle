<?php

namespace Entity\TheCodeine\NewsBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AttachmentTranslationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\FileBundle\Entity\AttachmentTranslation');
    }
}
