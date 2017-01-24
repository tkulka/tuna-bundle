<?php

namespace Model\TheCodeine\ImageBundle\Model;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Gaufrette\Filesystem;

class ImageManagerSpec extends ObjectBehavior
{

    function let(Filesystem $filesystem)
    {
        $this->beConstructedWith($filesystem, $basePath = '');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\ImageBundle\Model\ImageManager');
    }
}
