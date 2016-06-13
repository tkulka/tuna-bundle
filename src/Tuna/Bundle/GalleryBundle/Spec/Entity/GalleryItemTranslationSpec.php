<?php

namespace Entity\TheCodeine\GalleryBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GalleryItemTranslationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\GalleryBundle\Entity\GalleryItemTranslation');
    }
}
