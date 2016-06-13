<?php

namespace Form\TheCodeine\GalleryBundle\Form;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GalleryTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\GalleryBundle\Form\GalleryType');
    }
}
